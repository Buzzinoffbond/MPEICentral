<h1>Редактировать статью, предложенную <a target="blank" href="<?=URL::site('user/'.HTML::chars($article['username']));?>"><?=HTML::chars($article['username']);?></a></h1>
<?php if(isset($message))
printf('<h3>%s</h3>',$message) ; ?>
<form method="POST" action="" enctype="multipart/form-data">
	<div class="editor-box">
		<div class="control-group">
			<label class="label">Название:</label>
			<input name="title" id="title" type="text" value="<?= HTML::chars($article['title']); ?>" required>
		</div>
		<div class="control-group">
			<label class="label" for="url_title">URL:</label>
			<input name="url_title" id="url_title" type="text" value="<?= URL::title(HelpingStuff::rusToLat(HTML::chars($article['title'])), '_'); ?>">
		</div>
		<div class="control-group">
			<label class="label" for="content_short">Вступление:</label>
			<textarea name="content_short" id="content_short"></textarea>
		</div>
		<div class="control-group">
			<label class="label" for="kdpv">КДПВ:</label>
			<input type="file" name="kdpv" id="kdpv" />
		</div>
        <div class="control-group">
			<label class="label" for="kdpv_description">Описание КДПВ:</label>
			<input type="text" name="kdpv_description" id="kdpv_description" value="" />
		</div>
		<input type="hidden" name="author_id" value="<?=$article['author_id'];?>">	
		<br>
		<textarea name="content" id="content"><?= HTML::chars($article['content']); ?></textarea>
		<input name="submit" type="submit" value="Опубликовать">
		<span class="delete right" ><a href="<?= URL::site('admin/article/proposed/?delete='.$article['id']); ?>">удалить</a></span>
	</div>
	<div class="clear"></div>
</form>
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

	});
</script>