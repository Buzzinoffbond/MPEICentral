<div class="layout-content">
</div>
<div class="contest-cover" style="background: #000 url(<?= URL::site('public/images/contests/'.$contest['id'].'/'.$contest['cover']);?>); background-size: cover; background-position: center;">
		<div class="contest-cover-title"><?= HTML::chars($contest['title']); ?></div>
</div>
<div class="content-text-block">
		<?= $contest['description']; ?>
</div>
<div class="contest-competitors">
    <form method="POST" action="<?=URL::site('/contest/'.$contest['id'].'-'.$contest['url_title']); ?>">
	<?php
    foreach($competitors as $competitor): ?>
    <div class="contest-competitor">
        	<a href="<?= URL::site('contest/'.$contest['id'].'-'.$contest['url_title'].'/'.$competitor['id'].'-'.$competitor['url_title'].'/');?>">
            	<img class="contest-competitor-img" alt="<?=HTML::chars($competitor['name']);?>" src="<?= URL::site('public/images/contests/'.$competitor['contest_id'].'/'.$competitor['id'].'/thumbnails/'.$competitor['filename']);?>">
            	<div class="contest-competitor-name"><?= HTML::chars($competitor['name']); ?></div>
            </a>
            <div class="contest-competitor-vote">
            <?php 
            if ($voteInfo[0] AND !in_array($competitor['id'], $voteInfo[1]))
            {
                echo '<button name="vote" value='.$competitor['id'].' class="vote-btn" type="submit">Голосовать</button>';
            }
            else if (in_array($competitor['id'], $voteInfo[1]))
            {
                echo "Голос отдан";
            }
            ?>
            </div>
    </div>
	<?php endforeach; ?>
</div>