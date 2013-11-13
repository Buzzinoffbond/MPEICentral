<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Pages extends Controller_Common {

    public function action_about()
    {
        $this->template->content = View::factory('pages/about');
        $this->template->title = 'О сайте';
    }
}