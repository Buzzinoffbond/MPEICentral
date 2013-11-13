<div class="footer-inner">
	<?php
	echo HTML::anchor(URL::site('about'),"О сайте");
	
	if (Auth::instance()->logged_in('admin'))
	{
		printf('<a href="%s">Админка</a>',URL::site('admin'));
	}
	echo HTML::anchor("http://artemknyazev.ru/","artemknyazev.ru" );
	?>
</div>