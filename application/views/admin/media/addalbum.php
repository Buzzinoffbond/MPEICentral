<h1>Добавить альбом</h1>
<h3><?php if(isset($message))
echo $message; ?></h3>
<?php foreach ($arr as $value) {
	echo $value.'<br>';
}?>
<form method="POST" action="" enctype="multipart/form-data">
		<div class="control-group">
			<label class="label" for="title">Название альбома:</label>
			<input name="title" id="title" type="text" required>
		</div>
		<div class="control_title_group">
			<label class="label" for="description">Описание:</label>
			<textarea name="description" id="description" style="width:60%"></textarea>
		</div>
		<input  name="images[]" type="file" multiple/><br>
		<input type='submit' name="submit" value="Сохранить">
</form>