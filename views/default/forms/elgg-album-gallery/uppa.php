<?php
/**
 * Add Album Form
 */
?>
<div>
    <label for="image_title">Album Title</label>
    <?php echo elgg_view('input/text', array('name' => 'album_title')); ?>
</div>

<div>
    <label for="image_description">Album Description</label>
    <?php echo elgg_view('input/longtext',array('name' => 'album_descr')); ?>
</div>
<div>
    <label for="image_upload">Images upload</label>
    <?php echo elgg_view('input/file', array('name' => 'img_upload[]', 'multiple' => 'multiple')); ?>
</div>
<?php
echo elgg_view('input/hidden', array(
  'name' => 'container_guid',
   'value' => elgg_get_logged_in_user_guid()
    ));
echo elgg_view('input/submit', array(
    'name' => 'uppa',
    'value' => 'Create Album'
));
?>