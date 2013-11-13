<?php
if (empty($shouts)) 
{
    echo 'empty';
}
elseif ($shouts=="false") 
{
    echo 'false';
}
else
{
    foreach($shouts as $shout): 
        if ($shout['pid']!=0)
        {
            printf('<div class="shout shout-child" id="%s" pid="%s" >',$shout['id'], $shout['pid']);
        }
        else
        {
            printf('<div class="shout" id="%s" pid="%s" >',$shout['id'], $shout['pid']);
        }
        ?>

    
        <a class="username-link" href=<?= URL::site('user/'.HTML::chars($shout['username']));?>>
            <img class="shout-userpic" src="<?= URL::site('public/images/userpics/'.$shout['userpic']); ?>">
            <span class="shout-author"><?php echo HTML::chars($shout['username']);?></span></a> <span class="shout-date"><?= HelpingStuff::humanisedate($shout['date'],'datetime'); ?></span><br />
        <div class="shout-message"><?= Text::auto_link(HTML::chars($shout['message'])); ?></div>
                    <div class="clear"></div>
    </div>
 
<?php endforeach;} ?>