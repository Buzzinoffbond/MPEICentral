<div class="layout-content">
		<h2 class="left">Настройки профиля</h2>
		<div class="clear"></div>
		<h3><?= $message; ?></h3>
</div>
<div class="grey-box">
	<div class="layout-content">
		<div class="profile-avatar">
<img src="<?= URL::site('public/images/userpics/'.$user_info['userpic']); ?>"><h3>Аватар</h3>
<form action="<?= URL::site('edit') ?>" method="post" enctype="multipart/form-data">
            <input type="file" name="avatar" id="avatar" />
            <input type="submit" name="update_avatar" id="update_avatar" value="Загрузить" />
</form>
</div>
		<div class="aboutinfo">
<form action="" method="POST">
	<h3 class="edit-info-title">Личные данные</h3>
	<div class="control-group">
		<div class="edit-info-label"><label for="real_name">Имя:</label></div>
		<input name="real_name" id="real_name" class="edit-info-input" type="text" value="<?= $user_info['real_name']; ?>">
	</div>
	<div class="control-group">
		<div class="edit-info-label"><label for="age">Дата рождения:</label></div>
		<input name="age" id="age" class="edit-info-input" type="text" value="<?= $user_info['age']; ?>">
	</div>
	<div class="control-group">
		<div class="edit-info-label"><label for="hometown">Родной город:</label></div>
		<input name="hometown" id="hometown" class="edit-info-input" type="text" value="<?= $user_info['hometown']; ?>">
	</div>
	<div class="control-group">
		<div class="edit-info-label"><label for="institute">Институт:</label></div>
		<select name="institute" id="institute" class="edit-info-input" type="text">
			<option id="inst0" value="0"></option>
			<option id="inst1" value="1">АВТИ</option>
			<option id="inst2" value="2">ИТАЭ</option>
			<option id="inst3" value="3">ИПЭЭФ</option>
			<option id="inst4" value="4">ИРЭ</option>
			<option id="inst5" value="5">ИЭТ</option>
			<option id="inst6" value="6">ИЭЭ</option>
			<option id="inst7" value="7">ЭнМИ</option>
			<option id="inst8" value="8">ГПИ</option>
			<option id="inst9" value="9">ИМЭЭП</option>
			<option id="inst10" value="10">ЦП ИИЭБ</option>
			<option id="inst11" value="11">ФДП</option>
			<option id="inst12" value="12">ЦП ИЛ</option>
			<option id="inst13" value="13">ЦП МЭИ-ФЕСТО</option>
		</select>
		<script>$('#inst<?= $user_info['institute']; ?>').attr('selected', 'selected');</script>
	</div>
	<div class="control-group">
		<div class="edit-info-label"><label for="group">Группа:</label></div>
		<input name="group" id="group" class="edit-info-input" type="text" value="<?= $user_info['group']; ?>">
	</div>
	<div class="control-group">
		<div class="edit-info-label"><label for="website">Вебсайт: &nbsp;http://</label></div>
		<input name="website" id="website" class="edit-info-input" type="text" value="<?= $user_info['website']; ?>">
	</div>
	<div class="control-group">
		<div class="edit-info-label"><label for="about">О себе:</label></div>
		<textarea name="about" id="about" class="edit-info-input" type="text"><?= $user_info['about']; ?></textarea>
	</div>
	<div class="clear"></div>
	<input name="submit_user_info" class="big-submit edit-info-btn" type="submit" value="Сохранить">
</form>
<form action="" method="POST">
	<h3 class="edit-info-title">Параметры входа</h3>
	<div class="control-group">
		<div class="edit-info-label"><label for="username">Имя пользователя:</label></div>
		<input name="username" id="username" class="edit-info-input" type="text" value="<?= $user->username; ?>"><?= Arr::get($errors, 'username'); ?>
	</div>
	<div class="control-group">
		<div class="edit-info-label"><label for="email">Электронная почта:</label></div>
		<input name="email" id="email" class="edit-info-input" type="email" value="<?= $user->email; ?>"><?= Arr::get($errors, 'email'); ?>
	</div>
	<div class="control-group">
		<div class="edit-info-label"><label for="password">Пароль:</label></div><?= Arr::path($errors, '_external.password'); ?>
		<input name="password" id="password" class="edit-info-input" type="password">
	</div>
	<div class="control-group">
		<div class="edit-info-label"><label for="password_confirm">Подтвердите пароль:</label></div><?= Arr::path($errors, '_external.password_confirm'); ?>
		<input name="password_confirm" id="password_confirm" class="edit-info-input" type="password">
	</div>

	<div class="clear"></div>
	<input name="submit_user_data" class="big-submit edit-info-btn" type="submit" value="Сохранить">
</form>
	<div class="control-group">
		<div class="edit-info-label"></div>
		<?=$vk_merge;?>
	</div>

	<div class="control-group">
		<div class="edit-info-label"></div>
		<a href="<?= URL::site('delete_me') ?>">Удалить профиль</a>
	</div>
</div>
<div class="clear"></div>
</div>
</div>
<div class="layout-content">
&nbsp;
</div>
<script>
$(document).ready(function(){
    $('#about').autosize({append: "\n"});   
});</script>
<script>
document.getElementById('username').onkeyup = function () {
	var reg = /[а-яА-ЯёЁ]/g; 
	if (this.value.search(reg) !=  -1) {
		this.value  =  this.value.replace(reg, '');
	}
}
</script>