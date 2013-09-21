<div class="layout-content">
	<div class="propose-article">
		<h1>Предложить статью</h1>
		<h3><?php if($message===TRUE)
		{
			echo 'Статья отправлена.';
		}
		elseif($message === 'error')
		{
			echo "Произошла ошибка.";
		}

		    				foreach ($errors as $error)
    				{
    					echo $error;
    				} ?></h3>

		<form method="POST" action="">
			<div class="editor-box">
				<div class="control-group">
					<label class="label" for="title">Название:</label>
					<input name="title" id="title" type="text" required>
				</div>
				<div class="control-group">
					<label class="label" for="content">Текст статьи:</label>
					<textarea name="content" id="content" required></textarea>
				</div>
				<div class="control-group">
					<label class="label"></label>
					<input name="submit" type="submit" class="big-submit" value="Отправить">
				</div>
			</div>
		</form>
	</div>
	<div class="propose-article-rules">
		<p>Если вам есть чем поделиться, заполните эту форму, и после проверки статья будет опубликована от вашего имени.</p>
		<p>Не забывайте прикладывать ссылки на изображения, если таковые имеются.</p>
	</div>
	<div class="clear"></div>
</div>
<script>
	$(document).ready(function()
	{
		$('#content').autosize({append: "\n"});
	});
</script>