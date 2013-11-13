<?php foreach($comments as $comment): ?>
 
    <div class="shout">
        <a class="username-link" href=<?= URL::site('user/'.HTML::chars($comment['username']));?>>
            <img class="shout-userpic" src="<?= URL::site('public/images/userpics/'.$comment['userpic']); ?>">
            <span class="shout-author"><?php echo HTML::chars($comment['username']);?></span></a> <span class="shout-date"><?= HelpingStuff::humanisedate($comment['date'],'datetime'); ?></span><br />
        <?= Text::auto_link(HTML::chars($comment['message'])); ?>
                    <div class="clear"></div>
    </div>
 
<?php endforeach; ?>
<?php
if(Auth::instance()->logged_in())
{
?>
    <form action="" method="post">
        Сообщение:</br>
        <textarea name="message" class="comment-textarea"></textarea><br>
        <input class="big-submit" name="add_comment" type="submit" value="Отправить" /><?= Arr::get($errors, 'message'); ?>
    </form>
<?php 
}
else{?>
<a href="<?= URL::site('login'); ?>">войдите</a> или <a href="<?= URL::site('register'); ?>">зарегестрируйтесь</a> чтобы оставлять комментарии

<?php }?> 