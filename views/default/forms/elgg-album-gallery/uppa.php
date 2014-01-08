<?php
/**
 * Add Image Form
 */
?>
<div>
    <label for="image_title">Image Title</label>
    <?php echo elgg_view('input/text', array('name' => 'img_title')); ?>
</div>

<div>
    <label for="image_description">Description</label>
    <?php echo elgg_view('input/longtext',array('name' => 'img_descr')); ?>
</div>
<div>
    <label for="image_upload">Image upload</label>
    <?php echo elgg_view('input/file', array('name' => 'img_upload')); ?>
</div>
<?php
echo elgg_view('input/hidden', array('name' => 'container_guid',
    'value' => elgg_get_logged_in_user_guid()
    ));
echo elgg_view('input/submit', array(
    'name' => 'uppa',
    'value' => 'Upload Image'
));
?>