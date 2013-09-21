<div class="layout-content">
    <h1>Shoutbox</h1>
<?php
if(Auth::instance()->logged_in())
{
?>
<div class="create-shout-box">
        Сообщение: <br />
        <textarea id="message"></textarea>
        <div class="control-group">
            <button id="submit" class="big-submit shout-submit">Отправить</button><?= Arr::get($errors, 'message'); ?>
        </div>
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
<div class="shouts" id="shouts">
<?php foreach($shouts as $shout): ?>
    <div class="shout" id="<?= $shout['id'];?>" >
        <a class="username-link" href=<?= URL::site('user/'.HTML::chars($shout['username']));?>>
            <img class="shout-userpic" src="<?= URL::site('public/images/userpics/'.$shout['userpic']); ?>">
            <span class="shout-author"><?= HTML::chars($shout['username']);?></span>
        </a>
        <span class="shout-date"><?= HelpingStuff::humanisedate($shout['date'],'datetime'); ?></span><br />
        <div class="shout-message"><?= Text::auto_link(HTML::chars($shout['message'])); ?></div>
        <div class="clear"></div>
    </div>
<?php endforeach; ?>
</div>
<div class="clear"></div>
</div>
<script>
$(document).ready(function(){
    $('#message').autosize({append: "\n"});

    function getshouts(){
        var lastShoutId = $(".shout:first").attr("id");
        $.get("<?= URL::site("/ajax/getshouts")?>/"+lastShoutId,
            function(data) 
            {
                if ( data == "false") 
                {
                    $("#error-fatal").slideDown("normal");
                    clearTimeout(autoupdate);
                }
                else
                {
                    if ( data != "empty")
                    {
                        $("#shouts").prepend(data);
                        $(".shout:first").hide().fadeIn("normal");
                    }
                }
            autoupdate = setTimeout(getshouts, 5000);
            }
        );
    }
    getshouts()
    $("#submit").click(function()
    {
        send = setTimeout(function(){
            $('#error-too-long').slideDown("normal");
            $("#message").removeAttr("disabled");
            $("#submit").removeAttr("disabled").removeClass('disabled');
        }, 8000);

        if (!$("#submit").is(':disabled') & $('#message').val().length != 0){
            clearTimeout(autoupdate);
            $("#submit").attr("disabled","disabled").addClass('disabled');
            $("#message").attr("disabled","disabled");
            $(".shoutbox-error").slideUp("normal"); 
            var message = $("#message").val(); 
            $.ajax({ 
                type: "POST", 
                dataType: "json", 
                url: "/ajax/addshout", 
                data: "message="+message, 
                success: function(response)
                { 
                    if (response.code == "error") 
                    {
                        $("#error").slideDown("normal");
                    }
                    if (response.code == "success") 
                    {
                        getshouts();
                        $("#message").val("").outerHeight(50).removeAttr("disabled");
                    }
                    clearTimeout(send);
                    $("#message").removeAttr("disabled");
                    $("#submit").removeAttr("disabled").removeClass('disabled');
                }
            });
        };
    });
});
</script>