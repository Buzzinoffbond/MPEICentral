<div id="layout-content">
<h1>Добавить статью</h1>
<h3><?php if(isset($message))
echo $message; ?></h3>
<form method="POST" action="" enctype="multipart/form-data">
	<div class="editor-box">
		<div class="control-group">
			<label class="label" for="title">Название:</label>
			<input name="title" id="title" type="text" required>
		</div>
		<div class="control-group">
			<label class="label" for="url_title">URL:</label>
			<input name="url_title" id="url_title" type="text">
		</div>
		<div class="control-group">
			<label class="label" for="content_short">Вступление:</label>
			<textarea name="content_short" id="content_short"></textarea>
		</div>
		<div class="control-group">
			<label class="label" for="kdpv">КДВП:</label>
			<input type="file" name="kdpv" id="kdpv" />
		</div>
		 <div class="control-group">
			<label class="label" for="kdpv_description">Описание КДПВ:</label>
			<input type="text" name="kdpv_description" id="kdpv_description" />
		</div>
		<br>
		<textarea name="content" id="content">Текст статьи</textarea>
	<input name="submit" type="submit" value="Сохранить">
	</div>
	<div class="clear"></div>
</form>
</div>
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