<?php defined('SYSPATH') or die('No direct script access.');
 
abstract class Controller_Admin_CommonAdmin extends Controller_Common {
 
    public $template = 'admin/admin_template';
 
    public function before()
    {
        parent::before();
        View::set_global('title', 'Админка');				
        View::set_global('description', 'Description');

        if(!Auth::instance()->logged_in('admin'))
        {
            HTTP::redirect('errors/404');
        }

        $this->template->content = '';
        $this->template->styles = array('style');
        $this->template->scripts = '';
    }
 
} // End Common