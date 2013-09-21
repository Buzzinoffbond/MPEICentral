<div class="layout-content">
<h2 class="album-title"><?= HTML::chars($album_info['title']);?></h2>
<div class="album-description"><?= HTML::chars($album_info['description']);?></div>
</div>
<div class="images">
<?php foreach($images as $image): ?>

    <div class="image">
        <a class="colorbox" rel="group" href="<?= URL::site('public/images/albums/'.$image['album_dir'].'/'.$image['filename']);?>">
            <img src="<?= URL::site('public/images/albums/'.$image['album_dir'].'/thumbnails/'.$image['filename']);?>">
        </a>
    </div>
<?php endforeach; ?>
<script>
$('a.colorbox').colorbox({rel:'group', transition:"elastic", width:"90%", height:"85%"});
</script>
</div>
<div class="clear"></div>
<div class="layout-content">
</div>
