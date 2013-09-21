<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Dashboard extends Controller_Admin_CommonAdmin {

    // Главная страница
    public function action_index()
    {
 
        $content = View::factory('admin/dashboard');

        $this->template->content = $content; 

    }

} // End Page

