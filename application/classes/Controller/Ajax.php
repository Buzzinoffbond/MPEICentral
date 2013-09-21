<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Ajax extends Controller {
 
    public function action_getshouts()
    {
        $content = View::factory('/components/newshouts')
                    ->bind('shouts',$shouts)
                    ->bind('errors',$errors);
        $id =$this->request->param('id');
        $shouts = Model::factory('Community')->get_new_shouts($id);
        $this->response->body($content);
    }
    public function action_addshout(){
    
        if($this->request->post())
        {
            $result = array('code'=>'error');
            if(Auth::instance()->logged_in())
                {
                    $user_id = Auth::instance()->get_user('id');
                    $message = trim($this->request->post('message'));
                    $post = Validation::factory($this->request->post());
                    $post-> rule('message', 'not_empty')
                         -> rule('message', 'max_length', array(':value', 500));
                    if($post -> check())
                    {
                        if(Model::factory('Community')->add_shout($user_id, $message))
                        {
                            $result['code'] = 'success';
                        }
                    }
                    else
                    {
                        $errors = $post -> errors('validation');
                    }
                    
                }
        $this->response->body(json_encode($result));
        }
    }
 
} // Comments