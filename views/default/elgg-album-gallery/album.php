<?php
if($vars['loop'])
{?>
<div class="gallery-album">
    <h3 class="titolino"><?php echo $vars['title'];?></h3>
    <a href="/elgg-album-gallery/show/<?php echo $vars['img_guid']; ?>"><img src="<?php echo $vars['icon']; ?>"/></a>
</div>
<?php
}
else
{?>
<div class="action">
    <a class="del" href="/elgg-album-gallery/delete/<?php echo $vars['album_guid'];?>"><?php echo $vars['action']; ?></a>
</div>
<?php
}?>