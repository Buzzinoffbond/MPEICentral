<div class="layout-content">
		<h2 class="left"><?= HTML::chars($user['username']); ?></h2>
		<?php 
		if(Auth::instance()->logged_in()){
		if ($logged_user->username==$user['username']){?>
		<span class="usereditlink"><?= HTML::anchor('edit', 'редактировать'); ?></span>
		<?php }} ?>
		<div class="clear"></div>
</div>
<div class="grey-box">
	<div class="layout-content">
		<div class="profile-avatar">
			<img src="<?= URL::site('public/images/userpics/'.$user['userpic']); ?>">
		</div>
		<ul class="aboutinfo">
			<?php
			if ($user['real_name'])
				printf('<li><h4>%s<h4></li>',HTML::chars($user['real_name']));
			if ($user['age'])
				printf('<li class="i-age">%s</li>',HelpingStuff::humanisedate($user['age'],'age'));
			if ($user['hometown'])
				printf('<li class="i-location">%s</li>',HTML::chars($user['hometown']));
			if ($user['institute'])
				{
					printf('<li class="i-university">%s',HelpingStuff::decodeInstitute($user['institute'],2));
					if ($user['group'])
						{printf('<br>%s</li>',HTML::chars($user['group']));}
					else{echo "</li>";}
				}
			if ($user['website'])
				printf('<li class="i-link"><a href="http://%s">%s</a></li>',HTML::chars($user['website']),HTML::chars($user['website']));
			if ($user['about'])
				printf('<li class="i-text">%s</li>',HTML::chars($user['about']));?>
		</ul>
		<div class="clear"></div>
	</div>

</div>
<div class="layout-content">
&nbsp;
</div>