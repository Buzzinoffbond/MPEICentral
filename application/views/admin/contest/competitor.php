<div id="layout-content">
<h1>Участник</h1>
<?php if(isset($messages))
foreach ($messages as $message) {
	printf('<h3>%s</h3>',$message);
}?>
<form method="POST" action="" enctype="multipart/form-data">
	<div class="editor-box">
		<div class="control-group">
			<label class="label" for="name">Имя:</label>
			<input name="name" id="name" type="text" value="<?= HTML::chars($competitor['name']);?>" required>
		</div>
		<div class="control-group">
			<label class="label" for="url_title">URL:</label>
			<input name="url_title" id="url_title" type="text" value="<?= HTML::chars($competitor['url_title']);?>">
		</div>
		<div class="control-group">
		<textarea name="description" id="description"><?=$competitor['description']; ?></textarea>
		</div>
		<input type="hidden" name='sort_photos' id="sort_photos">
		<div class="control_group">
			<label class="label" for="description"></label>
			<input type='submit' name="update_data" value="Обновить">&nbsp;<button class="big-submit upload-btn" id="show-box">Загрузить файлы..</button>
		</div>
	</div>
	<div class="clear"></div>
</form>

<ul id="list">
<?php foreach($images as $image): ?>
	<li class="photo-album" id="sort_<?= $image['id'] ?>">
	<div class="edit-photo-box" id="edit-photo-box-<?= $image['id'] ?>" onclick="toggleEditBox(<?=$image['id'] ?>)"></div>
	<ul class="edit-photo-box-items" id="edit-items-<?= $image['id'] ?>">
		<li><a href="<?=URL::site('admin/contest/competitor/'.$competitor['id'].'?delete_photo='.$image['id']);?>">Удалить</a></li>
		<li><a href="<?=URL::site('admin/contest/competitor/'.$competitor['id'].'?make_default='.$image['id']);?>">Сделать основной</a></li>
	</ul>
		<a href="<?= URL::site('public/images/contests/'.$competitor['contest_id'].'/'.$competitor['id'].'/'.$image['filename'])?>">
			<div class="photo-album-cover">
				<img src="<?= URL::site('public/images/contests/'.$competitor['contest_id'].'/'.$competitor['id'].'/thumbnails/'.$image['filename'])?>">
			</div>
		</a>
	</li>
<?php endforeach; ?>
</ul>

<div class="clear"></div>
<p><span class="delete"><a href="#">Удалить участника</a></span></p>
<div class="floatbox" id="upload-box"><div id="uploader">Ваш браузер не поддерживает HTML5,Flash загрузку.</div></div>
</div>
<script>
$(document).ready(function()
{
		$("#name").syncTranslit(
		{
			destination: "url_title",
			urlSeparator: "_"
		});
		CKEDITOR.replace( "description" ,{
			width: 720,
			extraPlugins: 'autogrow',
			// Remove the Resize plugin as it does not make sense to use it in conjunction with the AutoGrow plugin.
			removePlugins: 'resize'
		});
	$( "#list" ).sortable(
    {
        stop: function() 
        {
        	var order = $('#list').sortable('serialize');
          	$("#sort_photos").val(order);
        }
    });
    $( "#list" ).disableSelection();
	toggleEditBox = function (id)
    {
      	$("#edit-items-"+id).toggle();
      	$("#edit-photo-box-"+id).toggle();
      	if ($("#edit-items-"+id).is(':visible'))
      	{
      		$("#edit-photo-box-"+id).show();
      	}
      	else
      	{
			$("#edit-photo-box-"+id).removeAttr('style');
      	}
    }
	$('#show-box').on('click',function(event){
    	event.preventDefault();
    	$('#upload-box').fadeIn();
	});  
	$(document).mouseup(function (e)
	{
    	var container = $("#upload-box");

    	if (!container.is(e.target) // if the target of the click isn't the container...
        	&& container.has(e.target).length === 0) // ... nor a descendant of the container
    	{
        	container.fadeOut();
    	}
	});
    $("#uploader").pluploadQueue({
        // General settings
        runtimes : 'html5,flash,html4',
        url : "<?= URL::site('admin/ajax/competitor_image?id='.$competitor['id']);?>",
        flash_swf_url : "<?= URL::site('public/js/plupload/plupload.flash.swf');?>",
        max_file_size : '3mb',
        // Specify what files to browse for
        filters : [
            {title : "Image files", extensions : "jpg,jpeg,gif,png"}
        ]
    });

    var uploader = $('#uploader').pluploadQueue();

    uploader.bind('FileUploaded', function() {
        if (uploader.files.length == (uploader.total.uploaded + uploader.total.failed))
        {
            location.reload();
        }
    });

});
</script>