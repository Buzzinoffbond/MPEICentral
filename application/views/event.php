<?php if($event): ?>
<div class="event-wrap">
<div id="event-top-media">
	<?php if(!empty($embedmedia)): ?>
	<div class="event-media">
		<div id="showcase" class="showcase">
			<?php foreach ($embedmedia as $media) {
				printf('<div class="showcase-slide"><div class="showcase-content">%s</div></div>',$media);
			}?>
		</div>
	</div>
	<?php endif; ?>
	<?php if (!empty($event['poster']))
	{
		printf('<div class="event-poster"><img src="%s"></div>',URL::site($event['poster']));
	}
	else
	{
		printf('<div class="event-poster"></div>');
	} ?>
<?php if (!empty($embedmedia)){echo '<div class="clear"></div>';}?>
</div>
<h1 class="event-title"><?= HTML::chars($event['title']);?></h1>
<div class="event-right-column">
	<div class="event-info-date"><?= HelpingStuff::humanisedate($event['date']);?></div>
	<div class="event-information">
		<ul>
			<?php
			if (!empty($event['start_time']))
			{
				printf('<li><span class="event-information-label">Начало:</span>%s</li>',HTML::chars($event['start_time']));
			}
			if (!empty($event['price']))
			{
				printf('<li><span class="event-information-label">Цена:</span>%s</li>',HTML::chars($event['price']));
			}
			if (!empty($event['place']))
			{
				printf('<li><span class="event-information-label">Место:</sapn>%s</li>',HTML::chars($event['place']));
			}
			if (!empty($event['link']))
			{
				$linkname=parse_url(HTML::chars($event['link']));
				printf('<li class="overhid"><span class="event-information-label">Ссылка:</span><a href="%s">%s</a></li>',HTML::chars($event['link']),$linkname['host']);
			}
			?>
		</ul>
	</div>
</div>
<div class="event-description">
	<p>
		<?php echo $event['content']; ?>
	</p>
</div>
<div class="clear"></div>
</div>
<div class="grey-box">
	<div class="layout-content">
		<?php echo $comments; ?>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		adjust_event_height = function(){
			var descr_min_height = $('.event-right-column').height() + $('.event-poster').height();
			$('.event-wrap').css('min-height',descr_min_height);
		}
		adjust_event_height();
		$(window).resize(function(){
	    	if ($(".mediaquery-status").css('width') === '600px' | $(".mediaquery-status").css('width') === '1024px')
	   		{
	   			adjust_event_height();
	   		}
	   	});
		
	});
</script>
<?php else: ?>
    <div class="layout-content">
		<p>Событие не найдено или не существует</p>
    </div>
<?php endif; ?>
