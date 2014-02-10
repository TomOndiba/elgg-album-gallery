<?php
/**
 * View page to show a singular image
 */
?>

<div class="titolone center"><?php echo $vars['title']; ?></div>
<div class="media">
    <img class="immagine-full" src="<?php echo $vars['image']; ?>"/>
    <div class="description"><p class="desc"><?php echo $vars['desc']; ?></p></div>
</div>
<?php
if($vars['guid_user'] == elgg_get_logged_in_user_guid()){?>
<div class="action">
    <a class="del" href="<?php echo elgg_get_site_url().'elgg-album-gallery/'.$vars['guid_user'].'/delete/image/'.$vars['guid'];?>"><?php echo $vars['action']; ?></a>
</div>
<?php } ?>
