<h1>Добавить альбом</h1>
<?php if(isset($messages))
foreach ($messages as $message) {
	printf('<h3>%s</h3>',$message);
}?>
<?php foreach ($arr as $value) {
	echo $value.'<br>';
}?>
<form method="POST" action="">
		<div class="control-group">
			<label class="label" for="title">Название альбома:</label>
			<input name="title" id="title" type="text" required>
		</div>
		<div class="control_title_group">
			<label class="label" for="description">Описание:</label>
			<textarea name="description" id="description" style="width:60%"></textarea>
		</div>
		<input type='submit' name="submit" value="Сохранить">
</form>