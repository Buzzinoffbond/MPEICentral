<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Comments extends Controller {
 
    public function action_index()
    {
        if($this->request->is_initial())
            Request::initial()->redirect(URL::site(''));
        $section=$this->request->param('section'); // events|articles
        $record_id = $this->request->param('id');
 
        $content = View::factory('/comments/comments')
                    ->bind('comments', $comments)
                    ->bind('errors',$errors);
 
        if($_POST)
        {
            if(Auth::instance()->logged_in())
                {
                    $user_id = Auth::instance()->get_user('id');
                    $message = trim($_POST['message']);
                    $post = Validation::factory($_POST);
                    $post
                        -> rule('message', 'not_empty')
                        -> rule('message', 'max_length', array(':value', 500));
                    if($post -> check())
                    {
                        Model::factory('Comments')->create_comment($section,$record_id, $user_id, $message);
                    }
                    else
                    {
                        $errors = $post -> errors('validation');
                    }
                      
                }
            
        }
        $_POST="";//чтобы повторно не отправлять комментарии при обновлении
        $comments = Model::factory('Comments')->get_comments($section,$record_id);
        $this->response->body($content);
    }
 
} // Comments
