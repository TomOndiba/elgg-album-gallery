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
<div class="action">
    <a class="del" href="/elgg-album-gallery/delete/<?php echo $vars['guid'];?>"><?php echo $vars['action']; ?></a>
</div>