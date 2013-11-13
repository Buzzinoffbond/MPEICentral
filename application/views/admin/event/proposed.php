<h1>Редактировать событие, предложенное <a target="blank" href="<?=URL::site('user/'.HTML::chars($event['username']));?>"><?=HTML::chars($event['username']);?></a></h1>
<?php if(isset($message))
printf('<h3>%s</h3>',$message) ; ?>
<form method="POST" action="" enctype="multipart/form-data">
	<div class="editor-box">
		<div class="control-group">
			<label class="label">Название:</label>
			<input name="title" id="title" type="text" value="<?= HTML::chars($event['title']); ?>" required>
		</div>
		<div class="control-group">
			<label class="label" for="url_title">URL:</label>
			<input name="url_title" id="url_title" type="text" value="<?= URL::title(HelpingStuff::rusToLat(HTML::chars($event['title'])), '_'); ?>">
		</div>
		<div class="control-group">
			<label class="label" for="altdate">Дата:</label>
			<input id="altdate" type="text" value="<?= strip_tags(HelpingStuff::humaniseDate(HTML::chars($event['date']))); ?>">
			<input name="date" id="date" type="hidden" value="<?= HTML::chars($event['date']); ?>">
		</div>
		<div class="control-group">
			<label class="label" for="start_time">Начало в:</label>
			<input name="start_time" id="start_time" type="text">
		</div>
		<div class="control-group">
			<label class="label" for="price">Цена:</label>
			<input name="price" id="price" type="text">
		</div>
		<div class="control-group">
			<label class="label" for="link">Ссылка:</label>
			<input name="link" id="link" type="text">
		</div>
		<div class="control-group">
			<label class="label" for="poster">Постер:</label>
			<input type="file" name="poster" id="poster" />
		</div>
		<div id="media-inputs">
			<div id="add-inputs-here"></div>
    		<span class="function-link media-poster" id="add-inputs">Добавить медиа-постер</span>		
    		<div class="media-poster-description">Поддерживаются ссылки на:<br>Youtube<br>Vimeo<br>Изображения(png, jpg, gif)</div>
    	</div>
		<input type="hidden" name="author_id" value="<?=$event['author_id'];?>">	
		<br>
		<textarea name="content" id="content"><?= HTML::chars($event['content']); ?></textarea>
		<input name="submit" type="submit" value="Опубликовать">
		<span class="delete right" ><a href="<?= URL::site('admin/event/proposed/?delete='.$event['id']); ?>">удалить</a></span>
	</div>
	<div class="clear"></div>
</form>
<script>
$("#add-inputs").click(function () {
	$('<div class="control-group" style="display: none;"><label class="label" for="media">Ссылка:</label><input name="media[]" type="text"></div>').hide().appendTo('#add-inputs-here').show('normal');				
	$("#add-inputs-here .control-group input").each(function(index, element){$(element).attr("id", "media"+index);});
	$("#add-inputs-here .control-group label").each(function(index, element){$(element).attr("for", "media"+index);});
});
</script>
<script>
	$(document).ready(function()
	{
		$("#title").syncTranslit(
		{
			destination: "url_title",
			urlSeparator: "_"
		});

		CKEDITOR.replace( "content" ,{
	width: 720,
	extraPlugins: 'autogrow',
	// Remove the Resize plugin as it does not make sense to use it in conjunction with the AutoGrow plugin.
	removePlugins: 'resize'
});

		$('#content_short').autosize({append: "\n"});

		$( "#altdate" ).datepicker({
			dateFormat: "DD, d MM, yy",
			altField: "#date",
			altFormat: "yy-mm-dd",
			defaultDate: new Date("<?=HTML::chars($event['date']); ?>"),
			minDate: new Date ()});
		$( "#altdate" ).datepicker( $.datepicker.regional[ "ru" ] );

	});
</script>