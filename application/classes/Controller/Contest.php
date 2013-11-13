<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Contest extends Controller_Common {

	public function action_contest()
	{
		if ($this->request->param('id'))
		{
			$this->template->content = View::factory('contest/contest')
			->bind('contest',$contest)
			->bind('competitors', $competitors)
			->bind('voteInfo',$voteInfo);
			$user = Auth::instance();
			$modelContest = Model::factory('Contest');
			$contest = $modelContest->get_info($this->request->param('id'));
			if (empty($contest))
        	{
            	throw new HTTP_Exception_404('Такого конкурса не существует');
        	}
        	$voteInfo = array(FALSE, array());
        	if ($user->logged_in())
        	{
        		$user_id = $user->get_user()->id;
        		$voteInfo = $modelContest->get_voteInfo($this->request->param('id'),$user_id);
        		if ($this->request->post('vote'))
        		{
        			try
        			{
        				$modelContest->vote($user_id,$this->request->post('vote'),$this->request->param('id'));
        			}
        			catch(Exceptions $e)
        			{
        				Kohana_Exception::log($e);
        			}
        		}
        	}
			$competitors = $modelContest->get_competitors($this->request->param('id'));
			
			$this->template->title = HTML::chars($contest['title']);
		}
		else
		{
			HTTP::redirect();
		}
	}
	public function action_competitor()
	{
		if ($this->request->param('competitor_id'))
		{
			$this->template->content = View::factory('contest/competitor')
			->bind('contest',$contest)
			->bind('competitor',$competitor)
			->bind('images',$images);
			
			$contest = Model::factory('Contest')->get_info($this->request->param('id'));
			if (empty($contest))
        	{
            	throw new HTTP_Exception_404('Такого конкурса не существует');
        	}
			$competitor = Model::factory('Contest')->get_competitor($this->request->param('competitor_id'));
			if (empty($competitor))
        	{
            	throw new HTTP_Exception_404('Такого участника не существует');
        	}
			$images = Model::factory('Contest')->get_competitor_photos($this->request->param('competitor_id'));
			$this->template->title = HTML::chars($competitor['name']).' - '. HTML::chars($contest['title']);
			$this->template->head='
			<link href="'.URL::base().'public/js/colorbox/colorbox.css" rel="stylesheet" type="text/css">
			<script src="'.URL::base().'public/js/colorbox/jquery.colorbox-min.js" type="text/javascript"></script>
			<script src="'.URL::base().'public/js/colorbox/jquery.colorbox-ru.js" type="text/javascript"></script>';
		}
		else
		{
			HTTP::redirect();
		}
	}
}