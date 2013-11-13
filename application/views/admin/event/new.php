<div id="layout-content">
<h1>Добавить событие</h1>
<h3><?php if(isset($message))
echo $message; ?></h3>
<form method="POST" action="" enctype="multipart/form-data">
	<div class="editor-box">
		<div class="control-group">
			<label class="label">Название:</label>
			<input name="title" id="title" type="text" required>
		</div>
		<div class="control-group">
			<label class="label" for="url_title">URL:</label>
			<input name="url_title" id="url_title" type="text">
		</div>
		<div class="control-group">
			<label class="label" for="altdate">Дата:</label>
			<input id="altdate" type="text">
			<input name="date" id="date" type="hidden">
		</div>
		<div class="control-group">
			<label class="label" for="start_time">Начало в:</label>
			<input name="start_time" id="start_time" type="text">
		</div>
		<div class="control-group">
			<label class="label" for="price">Цена:</label>
			<input name="price" id="price" type="text">
		</div>
		<div class="control-group">
			<label class="label" for="link">Ссылка:</label>
			<input name="link" id="link" type="text">
		</div>
		<div class="control-group">
			<label class="label" for="poster">Постер:</label>
			<input type="file" name="poster" id="poster" />
		</div>
		<div id="media-inputs">
			<div id="add-inputs-here"></div>
    		<span class="function-link media-poster" id="add-inputs">Добавить медиа-постер</span>		
    		<div class="media-poster-description">Поддерживаются ссылки на:<br>Youtube<br>Vimeo<br>Изображения(png, jpg, gif)</div>
    	</div>
	</div>
		<textarea name="content" id="content"></textarea>
		<script>
    	    CKEDITOR.replace( "content" ,{
			extraPlugins: 'autogrow',
			// Remove the Resize plugin as it does not make sense to use it in conjunction with the AutoGrow plugin.
			removePlugins: 'resize'
			});
    	</script>
    	<script>
    			$("#add-inputs").click(function () {
    				$('<div class="control-group" style="display: none;"><div class="label"><label for="media">Ссылка:</label></div><input name="media[]" type="text"></div>').hide().appendTo('#add-inputs-here').show('normal');
				
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
					minDate: new Date ()});
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
		<input name="submit" type="submit" class="right" value="Сохранить">
	</div>
	<div class="clear"></div>
</form>

</div>