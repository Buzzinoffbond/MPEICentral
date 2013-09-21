<div class="login-box">
<? if ($message) : ?>
	<h3 class="message">
		<?= $message; ?>
	</h3>
<? endif; ?>

<form method="POST" action="<?= URL::site('register');?>">

<fieldset>
	<label for="username">Имя пользователя:</label>
	<div class="error">
		<?= Arr::get($errors, 'username'); ?>
	</div>
	<?php if(!empty($_POST['username']))
	{
		printf('<input name="username" id="username" type="text" value="%s" required>',HTML::chars($_POST['username']));
	}
	else
	{
		echo '<input name="username" id="username" type="text" required>';
	}
?>
</fieldset>
<fieldset>
	<label for="email">Email:</label>
	<div class="error">
		<?= Arr::get($errors, 'email'); ?>
	</div>
	<?php if(!empty($_POST['email']))
	{
		printf('<input name="email" type="email" value="%s" required>',HTML::chars($_POST['email']));
	}
	else
	{
		echo '<input name="email" type="email" required>';
	}
?>
</fieldset>
<fieldset>
	<label for="password">Пароль:</label>
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
	<input type="submit" class="big-submit loginbtn" name="create" value="Зарегистрироваться">
</fieldset>

</form>
<p>Или <?= HTML::anchor('login', 'войти'); ?>, если у вас уже есть аккаунт.</p>
</div>
<script>
document.getElementById('username').onkeyup = function () {
	var reg = /[а-яА-ЯёЁ]/g; 
	if (this.value.search(reg) !=  -1) {
		this.value  =  this.value.replace(reg, '');
	}
}
</script>
