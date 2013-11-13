<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Comments extends Controller {
 
    public function action_index()
    {
        if($this->request->is_initial())
        {
            Request::initial()->redirect(URL::site(''));
        }
        $section=$this->request->param('section'); // events|articles
        $record_id = $this->request->param('id');
 
        $content = View::factory('/comments/comments')
                    ->bind('comments', $comments)
                    ->bind('errors',$errors);
        $data = $this->request->post('data');
        if($data['add_comment'])
        {
            if(Auth::instance()->logged_in())
            {   

                $user = Auth::instance()->get_user('id');
                $message = trim($data['message']);
                $post = Validation::factory($data);
                $post
                    -> rule('message', 'not_empty')
                    -> rule('message', 'max_length', array(':value', 500));
                if($post -> check())
                {
                    Model::factory('Comments')->create_comment($section, $record_id, $user->id, $message);
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
