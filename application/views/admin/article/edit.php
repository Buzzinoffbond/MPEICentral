<h1>Редактировать статью</h1>
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
			<input name="url_title" id="url_title" type="text" value="<?= HTML::chars($article['url_title']); ?>">
		</div>
		<div class="control-group">
			<label class="label" for="content_short">Вступление:</label>
			<textarea name="content_short" id="content_short"><?= HTML::chars($article['content_short']); ?></textarea>
		</div>

		<div class="control-group">
			<label class="label" for="kdpv">КДПВ:</label>
			<input type="file" name="kdpv" id="kdpv" /><span class="delete" ><a id="delete_kdpv" href="<?= URL::site('admin/article/deletekdpv/'.$article['id']);?>">удалить кдвп</a></span>
		</div>
        <?php if (!empty($article['kdpv']))
        {
            printf('<img src="%s" class="kdpv"><div class="clear"></div>', URL::site($article['kdpv']));
        }
        ?>
        <div class="control-group">
			<label class="label" for="kdpv_description">Описание КДПВ:</label>
			<input type="text" name="kdpv_description" id="kdpv_description" value="<?= HTML::chars($article['kdpv_description']); ?>" />
		</div>		
		<br>
		<textarea name="content" id="content"><?= $article['content']; ?></textarea>

    	<span class="delete left" ><a href="<?= URL::site('admin/article/delete/'.$article['id']); ?>">удалить</a></span>
		<input name="submit" type="submit" class="right" value="Обновить">
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