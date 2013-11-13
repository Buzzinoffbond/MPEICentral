<div class="medium-box">
<? if ($message) : ?>
	<h3 class="message">
		<?= $message; ?>
	</h3>
<? endif; ?>

<form method="POST" action="<?= URL::site('request_pass_reset');?>">

<fieldset>
	<label for="username">email:</label>
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
	<input type="submit" class="big-submit loginbtn" name="submit" value="Восстановить пароль">
</fieldset>
</form>
<p>Вспомнили пароль? <?= HTML::anchor('login', 'Войти'); ?>.</p>

</div>