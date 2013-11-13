<div class="medium-box">
<? if ($message) : ?>
	<h3 class="message">
		<?= $message; ?>
	</h3>
<? endif; ?>

<form method="POST" action="<?= URL::site('reset_pass?t='.$token);?>">

<fieldset>
	<label for="password">Новый пароль:</label>
	<div class="error">
		<?= Arr::path($errors, '_external.password'); ?>
	</div>
	<input name="password" type="password" required>
</fieldset>
<fieldset>
	<label for="password_confirm">Подтвердите пароль:</label>
	<div class="error">
		<?= Arr::path($errors, '_external.password_confirm'); ?>
	</div>
	<input name="password_confirm" type="password" required>
</fieldset>
<fieldset>
	<input type="submit" class="big-submit loginbtn" name="submit" value="Сохранить новый пароль">
</fieldset>
</form>

</div>