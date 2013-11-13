<div class="layout-content">
    <h1>Shoutbox</h1>
<?php
if(Auth::instance()->logged_in())
{
?>
<div class="message-box create-shout-box">
        Сообщение: <br />
        <textarea class="message" pid='0'></textarea>
        <button class="big-submit shout-submit">Отправить</button><?= Arr::get($errors, 'message'); ?>
        <div class="clear"></div>
</div>
<?php 
}
else{?>
<a href="<?php echo URL::site('login'); ?>">войдите</a> или <a href="<?php echo URL::site('register'); ?>">зарегестрируйтесь</a> чтобы оставлять комментарии

<?php }?>
<div class="shoutbox-error" id="error">Произошла ошибка. Попробуйте еще раз.</div>
<div class="shoutbox-error" id="error-too-long">Слишком долго, возможно проблемы с соединением. Попробуйте отправить еще раз.</div>
<div class="shoutbox-error" id="error-fatal">Произошла ошибка, автообновление не работает.<br><a style="font-weight:normal;" href="<?=URL::site('/community');?>">Попробуйте перезагрузить страницу</a></div>
<?php
echo '<div class="shouts" id="shouts">';
$count = count($shouts);
$lastShoutId = 1;
for ($i = 0; $i< $count; $i++)
{ 
	if($shouts[$i]['id']>$lastShoutId)
	{
		$lastShoutId=$shouts[$i]['id'];
	}
    if ($shouts[$i]['pid']==0)
    {
        printf('<div class="thread" thread="%s"><div class="shout" id="%s" pid="0">
            <a class="username-link" href=%s>
                <img class="shout-userpic" src="%s">
                <span class="shout-author">%s</span>
            </a>
            <span class="shout-date">%s</span><br />
            <div class="shout-message">%s</div>
            <div class="clear"></div>
        </div>',
        $shouts[$i]['id'],
        $shouts[$i]['id'],
        URL::site('user/'.HTML::chars($shouts[$i]['username'])),
        URL::site('public/images/userpics/'.$shouts[$i]['userpic']),
        HTML::chars($shouts[$i]['username']),
        HelpingStuff::humanisedate($shouts[$i]['date'],'datetime'),
        Text::auto_link(HTML::chars($shouts[$i]['message'])));

        $childs = 0;
        for ($k = $count-1; $k >= 0; $k=$k-1)
        { 
            if ($shouts[$k]['pid']==$shouts[$i]['id'])
            {   
                $childs++;
                printf('<div class="shout shout-child" id="%s" pid="%s">
                    <a class="username-link" href=%s>
                        <img class="shout-userpic" src="%s">
                        <span class="shout-author">%s</span>
                    </a>
                    <span class="shout-date">%s</span><br />
                    <div class="shout-message">%s</div>
                    <div class="clear"></div>
                </div>',
                $shouts[$k]['id'],
                $shouts[$k]['pid'],
                URL::site('user/'.HTML::chars($shouts[$k]['username'])),
                URL::site('public/images/userpics/'.$shouts[$k]['userpic']),
                HTML::chars($shouts[$k]['username']),
                HelpingStuff::humanisedate($shouts[$k]['date'],'datetime'),
                Text::auto_link(HTML::chars($shouts[$k]['message'])));
            }
        }
        printf('<div class="shout-replybox"></div>');
        echo "</div>";
    }
}
printf('<input type="hidden" id="lastShoutId" value="%s">',$lastShoutId);
?>
</div>
<div class="clear"></div>
</div>
<script>
$(document).ready(function(){
    function getshouts(){
        var lastShoutId = $("#lastShoutId").val();
        $.get("<?= URL::site("/ajax/getshouts")?>/"+lastShoutId,
            function(data) 
            {
                autoupdate = setTimeout(getshouts, 5000);
                if ( data == "false") 
                {
                    $("#error-fatal").slideDown("normal");
                    clearTimeout(autoupdate);
                }
                else
                {
                    if ( data != "empty")
                    {
                        var shouts = $(data).toArray( ".shout" );
                        var lastNewShoutId = lastShoutId;
                        $.each(shouts,function(index,value)
                        {
                            var pid = $(value).filter('.shout').attr("pid");
                            var id = $(value).filter('.shout').attr("id");

                            if (id>lastNewShoutId)
                            {
                                lastNewShoutId = id;
                            }
                            if ($(".thread[thread="+pid+"]").length>0)
                            {
                                $(value).insertAfter($(".thread[thread="+pid+"] .shout:last")).hide().fadeIn();
                            }
                            else if(pid == 0)
                            {
                                $('<div class="thread" thread="'+id+'"><div class="shout-replybox"></div></div>').prependTo('#shouts').prepend(value).hide().fadeIn();
                            }
                            $("#lastShoutId").val(lastNewShoutId);
                        });
                        checkforms();
                    }
                }
            }
        );
    }
    getshouts();
    <?php if (Auth::instance()->logged_in()):?>
    function checkforms()
    {
    	$('#shouts .thread').each(function(){
    		if (($(this).find('.shout-child, .answer-link').length==0) && ($(this).filter('textarea').length==0))
    		{
    			$(this).find('.shout-replybox').html('<a class="answer-link" href="#">Комментировать</a>');
    		}
    		else if(($(this).find('.shout-child').length!=0) && ($(this).filter('textarea').length==0))
    		{
    			$(this).find('.shout-replybox').html('<input class="shout-input-placeholder" placeholder="Комментировать.." type="text">');
    		}
    	});
    }
    function createShoutBox(target)
    {
    	var replyBox = $(target).parents('.shout-replybox')
    	var threadID = $(replyBox).parents('.thread').attr('thread');
        $(target).hide();
        $(replyBox).append('<div class="message-box"><textarea class="message" pid="'+threadID+'"></textarea><button class="big-submit shout-submit">Отправить</button><div class="clear"></div></div>');
        $(replyBox).find('textarea').focus();
        $('.message').autosize({append: "\n"});
        //hide if click somewhere else
        $(document).mouseup(function (e)
        {
            if (!replyBox.is(e.target) // if the target of the click isn't the container...
                && replyBox.has(e.target).length === 0) // ... nor a descendant of the container
            {
                if ($(replyBox).find('.message').val()==0)
                {
                	$(replyBox).find('.message-box').remove();
                	$(target).show();
                }
            }

        });
    }
    function sendShout(messageBox)
    {
        var message = $.trim($(messageBox).find('textarea').val());
        var pid = $(messageBox).find('textarea').attr('pid');
        if (!$(messageBox).find(".shout-submit").is(':disabled') && message)
        {
        	send = setTimeout(function(){
        	    $('#error-too-long').slideDown("normal");
        	    $(".message").removeAttr("disabled");
        	    $(".shout-submit").removeAttr("disabled").removeClass('disabled');
        	}, 8000);
        	
            clearTimeout(autoupdate);
            $(messageBox).find(".shout-submit").attr("disabled","disabled").addClass('disabled');
            $(messageBox).find(".message").attr("disabled","disabled");
            $(".shoutbox-error").slideUp("normal");
            $.ajax({ 
                type: "POST", 
                dataType: "json", 
                url: "/ajax/addshout", 
                data: { message: message, pid: pid } ,
                success: function(response)
                { 
                    if (response.code == "error") 
                    {
                        $("#error").slideDown("normal");
                    }
                    if (response.code == "success") 
                    {
                        getshouts();
                        checkforms();
                        $(messageBox).find('textarea').val("").outerHeight(50).removeAttr("disabled");
                    }
                    clearTimeout(send);
                    $(".message").removeAttr("disabled");
        		    $(".shout-submit").removeAttr("disabled").removeClass('disabled');
                }
            });

        };
    }
    checkforms();
    $('.message').autosize({append: "\n"});
    $('.layout-content').on('click','.answer-link',function(e){
    	e.preventDefault();
        createShoutBox(this);
    });
    $('.layout-content').on('click','.shout-replybox input',function(){
    	createShoutBox(this);
    });
    $('.layout-content').on('click','.shout-submit',function(){
    	var messageBox = $(this).parents('.message-box');
    	sendShout(messageBox);
    });
    <?php endif; ?>
});
</script>