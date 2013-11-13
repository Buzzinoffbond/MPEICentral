<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Community extends Controller_Common {

	public function action_index(){

		$content=View::factory('community')
					->bind('shouts',$shouts)
					->bind('errors',$errors);

		$shouts=Model::factory('Community')->get_shouts(15);

        $this->template->head ='<script type="text/javascript" src="'.URL::site("public/js/autosize-master/jquery.autosize-min.js").'"></script>';
        $this->template->title = 'Shoutbox';
		$this->template->content = $content;
	}
}