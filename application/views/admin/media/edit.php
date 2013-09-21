<h1>Альбом</h1>
<form method="POST" action="" enctype="multipart/form-data">
		<div class="control-group">
			<label class="label" for="title">Название альбома:</label>
			<input name="title" id="title" type="text" value="<?= HTML::chars($album_info['title']);?>" required>
		</div>
		<div class="control_title_group">
			<label class="label" for="description">Описание:</label>
			<textarea name="description" id="description" style="width:60%"><?= HTML::chars($album_info['description']);?></textarea>
		</div>
		<input type="hidden" name='sort_photos' id="sort_photos">
		<div class="control_group">
			<label class="label" for="description"></label>
			<input type='submit' name="update_info" value="Обновить">
		</div>

	
</form>


<ul id="list">
<?php foreach($images as $image): ?>
	<li class="photo-album" id="sort_<?= $image['id'] ?>">
	<div class="edit-photo-box" id="edit-photo-box-<?= $image['id'] ?>" onclick="toggleEditBox(<?=$image['id'] ?>)"></div>
	<ul class="edit-photo-box-items" id="edit-items-<?= $image['id'] ?>">
		<li><a href="<?=URL::site('/admin/media/setcover/'.$album_info['id'].'?photo_id='.$image['id']);?>">Сделать обложкой</a></li>
		<li><a href="<?=URL::site('/admin/media/deletephoto/'.$album_info['id'].'?photo_id='.$image['id']);?>">Удалить</a></li>
	</ul>
		<a href="<?= URL::site('public/images/albums/'.$image['album_dir'].'/'.$image['filename'])?>">
			<div class="photo-album-cover">
				<img src="<?= URL::site('public/images/albums/'.$image['album_dir'].'/thumbnails/'.$image['filename'])?>">
			</div>
		</a>
	</li>
<?php endforeach; ?>
</ul>
<div class="clear"></div>
<form method='post' action='' enctype="multipart/form-data">
	<input  name="images[]" type="file" multiple/>
	<input type='submit' name="add_images" value="Загрузить в альбом">
</form>
<p><span class="delete"><a href="<?= URL::site('admin/media/delete/'.$album_info['id']);?>">Удалить альбом</a></span></p>
<script>
$(document).ready(function(){
    $('#description').autosize({append: "\n"}); 
      $( "#list" )
      .sortable(
      {
        stop: function() 
        {
        	var order = $('#list').sortable('serialize');
          	$("#sort_photos").val(order);
        }
      });
      $( "#list" ).disableSelection();

      toggleEditBox = function (id)
      {
      	
      	$("#edit-items-"+id).toggle();
      	$("#edit-photo-box-"+id).toggle();
      	if ($("#edit-items-"+id).is(':visible'))
      	{
      		$("#edit-photo-box-"+id).show();
      	}
      	else
      	{
			$("#edit-photo-box-"+id).removeAttr('style');
      	}
      	
      }
	});
</script>