<div class="medium-box">
<? if ($message) : ?>
	<h3 class="message">
		<?= $message; ?>
	</h3>
<? endif; ?>

<form method="POST" action="<?= URL::site('login');?>">

<fieldset>
	<label for="username">Имя пользователя или email:</label>
	<?php if(!empty($_POST['username']))
	{
		printf('<input name="username" type="text" value="%s" required>',HTML::chars($_POST['username']));
	}
	else
	{
		echo '<input name="username" type="text" required>';
	}
?>
</fieldset>
<fieldset>
	<label for="password">Пароль:</label>
	<input name="password" type="password" required>
</fieldset>
<fieldset>
	<input name="remember" type="checkbox" checked="checked"><label class="inline" for="remember">Запомнить меня</label>
	<span class="right"><?= HTML::anchor('request_pass_reset', 'Забыли пароль?'); ?></fieldset><span>
</fieldset>
<fieldset>
	<input type="submit" class="big-submit loginbtn" name="submit" value="Войти">
</fieldset>
</form>
<?= $vk_link;?>
<p>Или <?= HTML::anchor('register', 'зарегестрироваться'); ?>.</p>

</div>