<?php defined('SYSPATH') or die('No direct script access.');
 
abstract class Controller_Common extends Controller_Template {
 
    public $template = 'main_template';

 
    public function before()
    {
        if (in_array($this->request->action(), array('login', 'register')))
        {
            $this->template = 'empty_template';
        }
        parent::before();
        View::set_global('title', 'MPEICentral.ru');				
        View::set_global('description', 'Description');
        $user = Auth::instance()->get_user();
        View::set_global('user', $user);
        $this->template->content = '';
        $this->template->styles = array('style');
        $this->template->scripts = '';
        $this->template->head    ='';

        if(Auth::instance()->logged_in('admin'))
        {
            session_start();        
            $_SESSION['KCFINDER'] = array();
            $_SESSION['KCFINDER']['disabled'] = false;
        }
        else
        {   
            session_start();
            $_SESSION['KCFINDER'] = array();
            $_SESSION['KCFINDER']['disabled'] = true;
        }
    }
 
} // End Common