<?php
/**
 * Action file to upload an image
 */

$titolo = get_input('album_title');
$descrizione = get_input('album_descr');
$owner = get_input('container_guid');

//Make an Album
$album = new ElggObject();
$album->subtype = "album-gallery";
$album->title = $titolo;
$album->description = $descrizione;
$album->access_id = ACCESS_PUBLIC;
$album->owner_guid = $owner;
$album_guid = $album->save();

if($album_guid)
{
  foreach ($_FILES as $image)
  {
    for($i=0; $i < count($image['name']);$i++)
    {
      $name_file = htmlspecialchars($image['name'][$i], ENT_QUOTES, 'UTF-8');
      //Make a file
      $file = new ElggFile();
      //This MUST to be "file" for file type
      $file->subtype = "file";
      $file->title = $name_file;
      $file->description = $name_file;
      $file->access_id = ACCESS_PUBLIC;
      $file->owner_guid = $owner;
      //Generate filename
      $prefix = "file/";
      $filestorename = elgg_strtolower(time().$image['name'][$i]);
      $file->setFilename($prefix . $filestorename);
      //Set Mimetype
      $mime_type = ElggFile::detectMimeType($image['tmp_name'][$i], $image['type'][$i]);
      $file->setMimeType($mime_type);
      //Set attributes
      $file->originalfilename = $image['name'][$i];
      $file->simpletype = file_get_simple_type($mime_type);
      // Open the file to guarantee the directory exists
      $file->open("write");
      $file->close();
      //Move file
      move_uploaded_file($image['tmp_name'][$i], $file->getFilenameOnFilestore());
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
          $thumb->setMimeType($image['type'][$i]  );

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
        add_entity_relationship($file->guid,'in_album',$album_guid);
    }
  }
  system_message("Album Created Successfull!");
  forward("elgg-album-gallery/$owner/album/$album_guid");
}
else
{
  system_message("Something wrong!");
  forward("elgg-album-gallery/add");
}
