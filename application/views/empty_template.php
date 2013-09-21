<!DOCTYPE html>
<html>
<head>
	<title><?= HTML::chars($title); ?> | MPEICentral</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="description" content="<?= HTML::chars($description); ?>" />
	<meta name="keywords" content="" />
	<meta name="viewport" content="width=device-width">
	<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<?php foreach($styles as $style): ?>
    <link href="<?php echo URL::base(); ?>public/css/<?php echo $style; ?>.css" 
    rel="stylesheet" type="text/css" />
<?php endforeach; ?>	
	<!--[if lt IE 9]>
    <script src="public/js/html5shiv.js"></script>
	<![endif]-->
	<?= $head; ?>
</head>
<body>
<div class="wrap">
<div class="header headerspace">
	<div class="header-inner">
	<a href="<?= URL::site(); ?>">
		<div class="logo">
			<img alt="MPEICentral" src="<?= URL::site("/public/images/MPEICentral-Concept.png"); ?>">
		</div>
	</a>
	<div class="nav">
		<nav>
			<ul>
				<li><a href="<?= URL::site('events'); ?>">События</a></li>
				<li><a href="<?= URL::site('articles'); ?>">Новости</a></li>
				<li><a href="<?= URL::site('media'); ?>">Медиа</a></li>
				<li><a href="<?= URL::site('community'); ?>">Сообщество</a></li>
<?php
if(Auth::instance()->logged_in())
{?>

<li class="user-bar">
	<a class="user-bar-link" href="<?php echo URL::site('user/'.$user->username.''); ?>"><img class="userpic-bar" src="<?php echo URL::site('public/images/userpics/'.$user->userpic); ?>"></a>
	<a class="user-bar-link username" href="<?php echo URL::site('user/'.$user->username.''); ?>"><?= $user->username; ?></a>
	<br><a class="user-bar-link logout" href="<?php echo URL::site('logout'); ?>">выход</a>
</li>
<?php }
else{?>
<li class="user-bar login"><a class="user-bar-link" href="<?php echo URL::site('login'); ?>">Вход</a></li>
<?php }?>
			</ul>
		</nav>
	</div>
<div class="menu-tuggle" id="menu-tuggle">Меню</div>
<div class="clear"></div>
</div>
</div>
	<?= $content; ?>
</div>
<div class="mediaquery-status"></div>
<script>
	$(document).ready(function(){
    	$("#menu-tuggle").click(function(){
    		$("nav").slideToggle("medium");
    	});

		$(window).resize(function(){
	    	if ($(".mediaquery-status").css('width') === '600px' | $(".mediaquery-status").css('width') === '1024px')
	   		{
	   			$("nav").css("display","block");
	   		}
	   		else
	   		{
	   			$("nav").css("display","none");
	   		}
	   	});
	});
</script>
</body>
</html>

