<div id="layout-content">
<h1>Добавить конкурс</h1>
<?php if(isset($message))
foreach ($messages as $message)
{
 	printf('<h3>%s</h3>',$message);
}?>
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
			<label class="label"></label>
			<input name="submit" type="submit" value="Сохранить">
		</div>
	
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
	});
</script>