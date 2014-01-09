<?php
/**
 * Library to use Elgg-gallery
 *
 */

/**
 * Get page components to add image.
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
 * Get page components to view all images
 *
 * @return  array
 */
function album_gallery_get_page_content_all()
{
    //Take entities from user
    $entita = elgg_get_entities(array('types' => array('object'),
                                      'subtypes' => array('file'),
                                      'owner_guid' => elgg_get_logged_in_user_guid(),
                                      'limit' => 0,
                                      'order_by' => 'time_created asc'
                                ));

    if(count($entita) > 0)
        foreach ($entita as $key)
            $immagine .= elgg_view('elgg-album-gallery/all',array('title' => $key['title'],
                                                            'icon' => $key->getIconURL('medium'),
                                                            'img_guid' => $key['guid']
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
        $immagine = elgg_view('elgg-album-gallery/view',array('title' => $img->title, 'desc' => $img->description, 'image' => $img->getIconURL('large'), 'guid' => $img->guid, 'action' => elgg_echo('gallery:delete:link')));
    }

    $return = array(
        'title' => $titolo,
        'content' => $immagine);

    return $return;
}

function album_gallery_get_page_content_delete($GUID)
{
    if(!$GUID || !$img = get_entity($GUID))
    {
        $immagine = elgg_view('elgg-album-gallery/error', array('error' => elgg_echo('gallery:no:imageshow')));
        $titolo = elgg_echo('gallery:title:error',array(elgg_echo('gallery:no:imageshow')));
    }
    else
    {
        //Delete image
        $img->delete();
        $message = elgg_echo("gallery:delete:img");
        system_message($message);
        forward("elgg-album-gallery/all");
    }
}

function album_gallery_get_page_content_album($GUID)
{
    if(!$GUID || !$img = get_entity($GUID))
    {
        $immagine = elgg_view('elgg-album-gallery/error', array('error' => elgg_echo('gallery:no:imageshow')));
        $titolo = elgg_echo('gallery:title:error',array(elgg_echo('gallery:no:imageshow')));
    }
    else
    {
        //Delete image
        $img->delete();
        $message = elgg_echo("gallery:delete:img");
        system_message($message);
        forward("elgg-album-gallery/all");
    }
}