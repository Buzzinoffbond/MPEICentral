<div class="layout-content">
<h1>Альбомы</h1>
</div>
<div class="albums">
<?php foreach($albums as $album): ?>

    <div class="album">
        	<a href="<?= URL::site('media/album/'.$album['id']);?>">
        	<? printf('<img src="%s" alt="%s">',URL::site('public/images/albums/'.$album['dir'].'/thumbnails/'.$album['cover']),HTML::chars($album['title']))?>
            	<div class="album-description"><?= HTML::chars($album['title']); ?></div>
        	</a>
    </div>
<?php endforeach; ?>

</div>
<div class="layout-content">
<?php  echo $pagination; ?>
<div class="clear"></div>
</div>