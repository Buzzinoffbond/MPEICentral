<h1>Альбомы</h1>
<div class="photo-album">
	<a href="<?= URL::site('admin/media/addalbum');?>">
		<div class="photo-album-cover"><img src="<?= URL::site('public/images/newalbum.png')?>"></div>
		<div class="photo-album-add">Добавить альбом</div>
	</a>
</div>
<?php foreach($albums as $album): ?>
<div class="photo-album">
	<a href="<?= URL::site('admin/media/edit/'.$album['id']);?>">
		<div class="photo-album-cover"><img src="<?= URL::site('public/images/albums/'.$album['dir'].'/thumbnails/'.$album['cover'])?>"></div>
		<div class="photo-album-title-bg"></div>
		<div class="photo-album-title"><?= $album['title'] ?></div>
	</a>
</div>
<?php endforeach; ?>
<div class="clear"></div>
<?php  echo $pagination; ?>
