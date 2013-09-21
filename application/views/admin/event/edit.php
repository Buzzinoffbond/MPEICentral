<h1>Редактировать событие</h1>
<h3><?php if(isset($message))
echo $message; ?></h3>
<form method="POST" action="" enctype="multipart/form-data">
	<div class="editor-box">
		<div class="control-group">
			<label class="label">Название:</label>
			<input name="title" id="title" type="text" value="<?= $event['title']; ?>" required>
		</div>
		<div class="control-group">
			<label class="label" for="url_title">URL:</label>
			<input name="url_title" id="url_title" type="text" value="<?= $event['url_title']; ?>">
		</div>
		<div class="control-group">
			<label class="label" for="altdate">Дата:</label>
			<input id="altdate" type="text" value="<?= HelpingStuff::humanisedate($event['date']); ?>">
			<input name="date" id="date" value="<?= $event['date']; ?>" type="hidden">
		</div>
		<div class="control-group">
			<label class="label" for="start_time">Начало в:</label>
			<input name="start_time" id="start_time" type="text" value="<?= $event['start_time']; ?>">
		</div>
		<div class="control-group">
			<label class="label" for="price">Цена:</label>
			<input name="price" id="price" type="text" value="<?= $event['price']; ?>">
		</div>
		<div class="control-group">
			<label class="label" for="link">Ссылка:</label>
			<input name="link" id="link" type="text" value="<?= $event['link']; ?>">
		</div>
		<div class="control-group">
			<label class="label" for="poster">Постер:</label>
			<input type="file" name="poster" id="poster" /><span class="delete" ><a id="delete_poster" href="<?= URL::site('admin/event/deleteposter/'.$event['id']);?>">удалить постер</a></span>
		</div>
		<?php if (!empty($event['poster']))
        {
            printf('<img src="%s" class="kdvp"><div class="clear"></div>', URL::site($event['poster']));
        }
        ?>
		<div id="media-inputs">
			<div id="add-inputs-here">
				<?php if($embedmedia){
					foreach($embedmedia as $media){
						printf('<div class="control-group"><label class="label" for="media">Ссылка:</label><input name="media[]" type="text" value="%s"></div>',$media);}}?>
			</div>
    		<span class="function-link media-poster" id="add-inputs">Добавить медиа-постер</span>		
    		<div class="media-poster-description">Поддерживаются ссылки на:<br>Youtube<br>Vimeo<br>Изображения(png, jpg, gif)</div>
    		<script>
    			$("#add-inputs").click(function () {
    				$('<div class="control-group" style="display: none;"><label class="label" for="media">Ссылка:</label><input name="media[]" type="text"></div>').hide().appendTo('#add-inputs-here').show('normal');
				
				$("#add-inputs-here .control-group input").each(function(index, element){$(element).attr("id", "media"+index);});
				$("#add-inputs-here .control-group label").each(function(index, element){$(element).attr("for", "media"+index);});
			});

    		</script>
    		<script>
  				$(function() {
    			$( "#altdate" ).datepicker({
    				dateFormat: "DD, d MM, yy",
    				altField: "#date",
    				altFormat: "yy-mm-dd",
    				defaultDate: new Date("<?=$event['date']; ?>") });
    			$( "#altdate" ).datepicker( $.datepicker.regional[ "ru" ] );
  				});
  			</script>
  			<script>
				$(document).ready(function(){
				  $("#title").syncTranslit({
				    destination: "url_title",
				    urlSeparator: "_"
				  });
				});
  			</script>
    	</div>
		<textarea name="content" id="content"><?= $event['content']; ?></textarea>
		<script>
    	    CKEDITOR.replace( "content" ,{
	extraPlugins: 'autogrow',
	// Remove the Resize plugin as it does not make sense to use it in conjunction with the AutoGrow plugin.
	removePlugins: 'resize'
});
    	</script>
    <span class="delete left" ><a href="<?= URL::site('admin/event/delete/'.$event['id']); ?>">удалить</a></span>
	<input name="submit" type="submit" class="right" value="Обновить">
	</div>
	<div class="clear"></div>

	
</form>