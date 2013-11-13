<div class="competitor-contest-wrap">
<?php printf('<a href="%s"><div class="competitor-contest" style="background: url(%s); background-size: cover; background-position: center;"><span class="matrix-contest-title">%s</span></div></a>',URL::site('/contest/'.$contest['id'].'-'.$contest['url_title']),URL::site('/public/images/contests/'.$contest['id'].'/'.$contest['cover']),HTML::chars($contest['title']));?>
</div>
<div class="layout-content">
<div class="competitor-img">
    <img  alt="<?= HTML::chars($competitor['name']); ?>" src="<?= URL::site('public/images/contests/'.$competitor['contest_id'].'/'.$competitor['id'].'/'.$competitor['filename'])?>">
</div>    
<div class="competitor-description">
<h1><?= HTML::chars($competitor['name']); ?></h1>
       <?= $competitor['description']; ?>
</div>
<div class="clear"></div>
<div class="competitor-photos">
<?php foreach($images as $image): ?>
    <div class="competitor-photo">
        <a class="colorbox" rel="group" href="<?= URL::site('public/images/contests/'.$competitor['contest_id'].'/'.$competitor['id'].'/'.$image['filename'])?>">
            <div class="photo-album-cover">
                <img src="<?= URL::site('public/images/contests/'.$competitor['contest_id'].'/'.$competitor['id'].'/thumbnails/'.$image['filename'])?>">
            </div>
        </a>
    </div>
<?php endforeach; ?>
<div class="clear"></div>
</div>
</div>
<script>
$(document).ready(function()
{
    $(".competitor-photo:nth-child(5n)").addClass("no-right-margin");
    $('a.colorbox').colorbox({rel:'group', transition:"elastic", width:"90%", height:"85%"});

});

</script>