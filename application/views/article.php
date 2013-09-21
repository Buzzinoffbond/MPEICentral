<?php if($article): ?>
<div class="article-wrap">
    <div class="article">
        <div class="article-date"><?= HelpingStuff::humanisedate($article["date"]);?></div>
        <h2><?= HTML::chars($article['title']); ?></h2>
        <?php if (!empty($article['kdpv']))
        {
            printf('<img src="%s" class="kdpv">', URL::site($article['kdpv']));
            if (!empty($article['kdpv_description'])) 
            {
                printf('<div class="kdpv_description">%s</div>',HTML::chars($article['kdpv_description']));
            }
        }
        ?>
        <?= $article['content'];?>
        <span class="article-author"><a href="<?=URL::site('user/'.HTML::chars($article['username']))?>"><?= HTML::chars($article['username']); ?></a></span>
        <div class="clear"></div>
    </div>
    <div class="article-comments">
        <?= $comments; ?>
    </div>
    <div class="clear"></div>
</div>     
<?php else: ?>
    <div style="padding:10px; margin-bottom:10px;">
		Статья не найдена или не существует
    </div>
<?php endif; ?>