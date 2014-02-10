<?php
if($vars['loop'])
{?>
<div class="gallery-album">
    <h3 class="titolino"><?php echo $vars['title'];?></h3>
    <a href="<?php echo elgg_get_site_url().'elgg-album-gallery/'.$vars['guid_user'].'/show/'.$vars['img_guid'];?>"><img src="<?php echo $vars['icon']; ?>"/></a>
</div>
<?php
}
else
{?>
<div class="action">
    <a class="del" href="<?php echo elgg_get_site_url().'elgg-album-gallery/'.$vars['guid_user'].'/delete/'.$vars['album_guid'];?>"><?php echo $vars['action']; ?></a>
</div>
<?php
}?>
