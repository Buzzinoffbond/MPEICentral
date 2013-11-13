<!DOCTYPE html>
<html>
	<head>
	<title><?php echo $title; ?> | newdkmpei</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<?php foreach($styles as $style): ?>
    <link href="<?php echo URL::base(); ?>public/css/admin/<?php echo $style; ?>.css" 
    rel="stylesheet" type="text/css" />
<?php endforeach; ?>
	<meta name="description" content="<?php echo $description; ?>" />
	<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
	<link rel="stylesheet" href="<?php echo URL::base(); ?>public/js/jquery-ui/css/jquery-ui-1.10.3.custom.css" />
  	<script src="<?php echo URL::base(); ?>public/js/jquery-ui/jquery-ui-1.10.3.custom.js"></script>
  	<script src="<?php echo URL::base(); ?>public/js/jquery-ui/jquery.ui.datepicker-ru.js"></script>
	<?= $head; ?>
	</head>
	<body>
		<div class="wrap">
<div class="left-panel">
	<ul class="nav-sections">
		<li class="nav-color-empty"><a href="<?= URL::site()?>">MPEICentral</a></li>
		<li class="nav-color-blue"><a href="<?= URL::site('admin/dashboard')?>">Панель управления</a></li>

		<li class="nav-color-pink"><a href="<?= URL::site('admin/event/')?>">События</a>
			<ul class="nav-sub-section">
				<li><a href="<?= URL::site('admin/event/new')?>">Добавить событие</a></li>
				<li><a href="<?= URL::site('admin/event/proposed')?>">Предложенные</a></li>
			</ul>
		</li>
		<li class="nav-color-green"><a href="<?= URL::site('admin/article/')?>">Новости</a>
			<ul class="nav-sub-section">
				<li><a href="<?= URL::site('admin/article/new')?>">Добавить статью</a></li>
				<li><a href="<?= URL::site('admin/article/proposed')?>">Предложенные</a></li>
			</ul>
		</li>
		<li class="nav-color-orange"><a href="<?= URL::site('admin/media/')?>">Медиа</a>
			<ul class="nav-sub-section">
				<li><a href="<?= URL::site('admin/media/addalbum')?>">Добавить альбом</a></li>
			</ul>
		</li>
		<li class="nav-color-deepblue"><a href="<?= URL::site('admin/contest/')?>">Конкурсы</a>
			<ul class="nav-sub-section">
				<li><?= HTML::anchor('/admin/contest/new', 'Добавить конкурс');  ?></li>
			</ul></li>
		<li class="nav-color-purple"><a href="<?= URL::site('admin/user/')?>">Пользователи</a></li>
	</ul>
</div>
<div class="a-head-bar">
	<div class="head-bar">
		<div class="user-bar">
			<a class="user-bar-link" href="<?php echo URL::site('user/'.$user->username.''); ?>"><img class="userpic-bar" src="<?php echo URL::site('public/images/userpics/'.$user->userpic); ?>"></a>
			<b><a class="user-bar-link" href="<?php echo URL::site('user/'.$user->username.''); ?>"><?= $user->username; ?></a></b>
			<br> <a class="user-bar-link" href="<?php echo URL::site('logout'); ?>">выход</a>
		</div>
	</div>
</div>
<div class="a-layout">
	<div class="content">
		<?php echo $content; ?>
	</div>
</div>
</div>
</body>
</html>

