<?php
/**
 * Action file to upload an image
 */

$titolo = get_input('album_title');
$descrizione = get_input('album_descr');
$owner = get_input('container_guid');

// If we don't have any files
if (empty($_FILES['img_upload']['name']))
{
    $error = elgg_echo('file:nofile');
    register_error($error);
    forward(REFERER);
}

//Make an Album
$album = new ElggObject();
$album->subtype = "album";
$album->title = $titolo;
$album->description = $descrizione;
$album->access_id = ACCESS_PUBLIC;
$album->owner_guid = $owner;
$album_guid = $album->save();

if($album_guid)
{
  foreach ($_FILES['img_upload'] as $image)
  {
    $name_file = htmlspecialchars($image['name'], ENT_QUOTES, 'UTF-8');
    //Make a file
    $file = new FilePluginFile();
    $file->subtype = "album_image";
    $file->title = $name_file;
    $file->description = $name_file;
    $file->access_id = ACCESS_PUBLIC;
    $file->owner_guid = $owner;
    //Generate filename
    $prefix = "album_image/";
    $filestorename = elgg_strtolower(time().$image['name']);
    $file->setFilename($prefix . $filestorename);
    //Set Mimetype
    $mime_type = ElggFile::detectMimeType($image['tmp_name'], $image['type']);
    $file->setMimeType($mime_type);
    //Set attributes
    $file->originalfilename = $image['name'];
    $file->simpletype = file_get_simple_type($mime_type);
    // Open the file to guarantee the directory exists
    $file->open("write");
    $file->close();
    //Move file
    move_uploaded_file($image['tmp_name'], $file->getFilenameOnFilestore());
    //Save file
    $file_guid = $file->save();

    //Make thumbnails
    if ($file_guid && $file->simpletype == "image")
    {
      $file->icontime = time();
      $thumbnail = get_resized_image_from_existing_file($file->getFilenameOnFilestore(), 60, 60, true);
      if ($thumbnail)
      {
        $thumb = new ElggFile();
        $thumb->setMimeType($image['type']);

        $thumb->setFilename($prefix."thumb".$filestorename);
        $thumb->open("write");
        $thumb->write($thumbnail);
        $thumb->close();

        $file->thumbnail = $prefix."thumb".$filestorename;
        unset($thumbnail);
      }

      $thumbsmall = get_resized_image_from_existing_file($file->getFilenameOnFilestore(), 153, 153, true);
      if ($thumbsmall)
      {
        $thumb->setFilename($prefix."smallthumb".$filestorename);
        $thumb->open("write");
        $thumb->write($thumbsmall);
        $thumb->close();
        $file->smallthumb = $prefix."smallthumb".$filestorename;
        unset($thumbsmall);
      }

      $thumblarge = get_resized_image_from_existing_file($file->getFilenameOnFilestore(), 600, 600, false);
      if ($thumblarge)
      {
        $thumb->setFilename($prefix."largethumb".$filestorename);
        $thumb->open("write");
        $thumb->write($thumblarge);
        $thumb->close();
        $file->largethumb = $prefix."largethumb".$filestorename;
        unset($thumblarge);
      }
    }
    if ($file_guid)
      add_entity_relationship($file_guid,'dentro',$album_guid);
  }
  system_message("Album Creato con successo!");
  forward("elgg-album-gallery/".$album_guid);
}
else
{
  system_message("Album Creato con successo!");
  forward("elgg-album-gallery/add");
}