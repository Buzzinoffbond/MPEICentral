<h2 class="h-title">
	<a href="<?= URL::site('user/'.$user['username']); ?>">
                    <?= HTML::chars($user['username']); ?>
    </a>
</h2>
<div class="edituser-profileavatar">
	<img src="<?= URL::site('public/images/userpics/'.$user['userpic']); ?>">
</div>

<ul class="edituser-aboutinfo">
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
<ul>
	<li><span class="delete"><a href="<?= URL::site('admin/user/delete/'.$user['username']); ?>">Удалить</li></span>
</ul>