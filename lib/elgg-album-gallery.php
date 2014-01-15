<?php
/**
 * Library to use Elgg-gallery
 *
 */

/**
 * Get page components to add Album and Images
 *
 * @return array
 */
function album_gallery_get_page_content_add()
{
    elgg_load_library('elgg:file');
    $vars = array('enctype' => 'multipart/form-data');
    $body_vars = file_prepare_form_vars();

    //Create Array values to return
    $return = array(
        'title' => elgg_echo('gallery:title:add'),
        'content' => elgg_view_form('elgg-album-gallery/uppa', $vars,$body_vars)
        );

    //Returns array
    return $return;
}

/**
 * Get page components to view all albums
 *
 * @return  array
 */
function album_gallery_get_page_content_all()
{
    //Take entities from user
    $entita = elgg_get_entities(array(
      'types' => array('object'),
      'subtypes' => array('album-gallery'),
      'owner_guid' => elgg_get_logged_in_user_guid(),
      'limit' => 0,
      'order_by' => 'e.time_created asc'
    ));
    if(count($entita) > 0)
        foreach ($entita as $key)
            $immagine .= elgg_view('elgg-album-gallery/all',array(
              'title' => $key->title,
              'descr' => $key->description,
              'album_guid' => $key->guid
            ));
    else
        $immagine = elgg_view('elgg-album-gallery/error', array('error' => elgg_echo('gallery:no:imagegallery')));

    $return = array(
        'title' => elgg_echo('gallery:title:showall'),
        'content' => $immagine);

    return $return;
}

/**
 * Get page components to view an image
 *
 * @return  array
 */
function album_gallery_get_page_content_show($GUID)
{
    if(!$GUID || !$img = get_entity($GUID))
    {
        $immagine = elgg_view('elgg-album-gallery/error', array('error' => elgg_echo('gallery:no:imageshow')));
        $titolo = elgg_echo('gallery:title:error',array(elgg_echo('gallery:no:imageshow')));
    }
    else
    {
        $titolo = elgg_echo('gallery:title:showone',array($img->title));
        $immagine = elgg_view('elgg-album-gallery/show',array('title' => $img->title, 'desc' => $img->description, 'image' => $img->getIconURL('large'), 'guid' => $img->guid, 'action' => elgg_echo('gallery:delete:link')));
    }

    $return = array(
        'title' => $titolo,
        'content' => $immagine);

    return $return;
}

/**
 * Delete Albums or images
 * @param  integer $GUID Album/image guid
 *
 * @return array
 */
function album_gallery_get_page_content_delete($GUID)
{
    if(!$GUID || !$img = get_entity($GUID))
    {
        $immagine = elgg_view('elgg-album-gallery/error', array('error' => elgg_echo('gallery:no:imageshow')));
        $titolo = elgg_echo('gallery:title:error',array(elgg_echo('gallery:no:imageshow')));
    }
    else
    {
        //Delete all
        $img->delete();
        $message = elgg_echo("gallery:delete:img");
        system_message($message);
        forward("elgg-album-gallery/all");
    }
}

/**
 * Show images from album
 * @param  integer $GUID Album guid
 * @return array
 */
function album_gallery_get_page_content_album($GUID)
{
    if(!$GUID)
    {
        $immagine = elgg_view('elgg-album-gallery/error', array('error' => elgg_echo('gallery:no:imageshow')));
        $titolo = elgg_echo('gallery:title:error',array(elgg_echo('gallery:no:imageshow')));
    }
    else
    {
      //Take entities from in_album relationship
      $entita = elgg_get_entities_from_relationship(array(
        'relationship' => 'in_album',
        'relationship_guid' => $GUID,
        'inverse_relationship' => true,
        'limit' => 0
        ));

      foreach ($entita as $key)
        $immagine .= elgg_view('elgg-album-gallery/album',array(
            'title' => $key->title,
            'icon' => $key->getIconURL('medium'),
            'img_guid' => $key->guid,
            'album_guid' => $GUID,
            'action' => "Delete Album",
            'loop' => true
          ));
      //To show delete action
      $immagine .= elgg_view('elgg-album-gallery/album',array(
            'album_guid' => $GUID,
            'action' => "Delete Album",
            'loop' => false
          ));
    }

    $return = array(
      'title' => elgg_echo('gallery:title:showall'),
      'content' => $immagine);
    return $return;
}