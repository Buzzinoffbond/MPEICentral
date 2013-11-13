<div id="layout-content">
<?php if(isset($messages))
foreach ($messages as $message) {
	printf('<h3>%s</h3>',$message);
}?>
<form action="<?=URL::site('/admin/contest/edit/'.$contest_info['id']) ?>" method="POST" enctype="multipart/form-data">
	<div class="contest-cover" <?php if (!empty($contest_info['cover'])) {
		printf('style="background: url(%s); background-size: cover; background-position: center;"',URL::site('public/images/contests/'.$contest_info['id'].'/'.$contest_info['cover']));
	}  ?>>
		<input class="contest-cover-input" id="title" name="title" type="text" value="<?= HTML::chars($contest_info['title']);?>">
		<input id="url_title" name="url_title" type="hidden" value="<?= HTML::chars($contest_info['title']);?>">
		<input class="contest-cover-file" name="cover" type="file">
	</div>
	<div class="contest-description"><textarea name="description" id="content"><?= $contest_info['description'];?></textarea></div>

	<input name="submit_contest_info" type="submit" value="Сохранить">
	<?php if ($contest_info['active']==0)
	{
		printf('<a href="%s">Включить</a>',URL::site('/admin/contest/edit/'.$contest_info['id'].'?toggle_active=1'));
	}
	else
	{
		printf('<a href="%s">Выключить</a>',URL::site('/admin/contest/edit/'.$contest_info['id'].'?toggle_active=1'));
	} 
	?>

</form>
	<h1>Участники голосования</h1>


	<?php foreach($competitors as $competitor): ?>
	<div class="competitor">
		<a href="<?= URL::site('admin/contest/competitor/'.$competitor['id']);?>">
			<div class="competitor-cover"><img src="<?= URL::site('public/images/contests/'.$competitor['contest_id'].'/'.$competitor['id'].'/thumbnails/'.$competitor['filename']);?>"></div>
			<div class="competitor-title-bg"></div>
			<div class="competitor-title"><?= HTML::chars($competitor['name']); ?></div>
		</a>
	</div>
	<?php endforeach; ?>
	<div class="competitor">
		<a href="#" id="addcompetitor">
			<div class="competitor-cover"><img src="<?= URL::site('public/images/new.png')?>"></div>
			<div class="competitor-add">Добавить Участника</div>
		</a>
	</div>
	<div class="floatbox" id="new-competitor">
		<h1>Добавить Участника</h1>
		<form id="add_competitor_form" method="POST" action="" enctype="multipart/form-data">
			<div class="control-group">
				<label class="label" for="name">Имя:</label>
				<input name="name" type="text" required>
			</div>
			<div class="control-group">
				<label class="label"></label>
				<input type='submit' name="submit_competitors_data" value="Сохранить">
			</div>
			
		</form>
	</div>
	
	<div class="clear"></div>
	<p><span class="delete"><a href="<?=URL::site('/admin/contest/edit/'.$contest_info['id'].'?delete_contest=1') ?>">Удалить конкурс</a></span></p>
</div>	
<script>
$(document).ready(function()
{
		$("#title").syncTranslit(
		{
			destination: "url_title",
			urlSeparator: "_"
		});
		CKEDITOR.replace( "content" ,{
			width: 720,
			extraPlugins: 'autogrow',
			// Remove the Resize plugin as it does not make sense to use it in conjunction with the AutoGrow plugin.
			removePlugins: 'resize'
		});


	$('#addcompetitor').on('click',function(event){
    	event.preventDefault();
    	$('#new-competitor').fadeIn();
	});  
	$(document).mouseup(function (e)
	{
    	var container = $("#new-competitor");

    	if (!container.is(e.target) // if the target of the click isn't the container...
        	&& container.has(e.target).length === 0) // ... nor a descendant of the container
    	{
        	container.fadeOut();
    	}
	});

});
</script>
