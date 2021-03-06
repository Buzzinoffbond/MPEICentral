<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Articles extends Controller_Common {
 
    public function action_index()
    {
        $articles = array();
        
        $content = View::factory('articles/articles')
                ->bind('articles', $articles)
                ->bind('pagination', $pagination);
        $total_items = Model::factory('Articles')->count_all();
        $pagination = Pagination::factory(array(
            'total_items' => $total_items,
            'items_per_page'=> 30,
            )
        );
        // Pass controller and action names explicitly to $pagination object
        $pagination->route_params(array('controller' => $this->request->controller(), 'action' => $this->request->action())); 
        // Get data
        $start=$pagination->offset;
        $nums=$pagination->items_per_page;
        $articles = Model::factory('Articles')->get_page($start,$nums);
        $this->template->content = $content;
        $this->template->title = 'Новости';
        $this->template->description = 'Новости МЭИ';

    }
 
    public function action_article()
    {
        $id = $this->request->param('id');
 
        $content = View::factory('articles/article')
                        ->bind('article', $article)
                        ->bind('table', $table)
                        ->bind('comments', $comments);
        $article = Model::factory('Articles')->get_article_by_id($id);
 
        $comments_url = 'comments/articles/' . $id;
        if ($this->request->post('add_comment'))
        {
            try
            {
                Request::factory($comments_url)
                    ->method(Request::POST)
                    ->post(array('data' => $this->request->post()))
                    ->execute();
            }
            catch(Exceptions $e)
            {
                //log it
            }
        }    
        $comments = Request::factory($comments_url)->execute();
 
        $this->template->content = $content;
        $this->template->title = $article['title'];
        $this->template->description = '';
    }

    public function action_propose(){
    	$user = Auth::instance();
    	if ($user->logged_in())
    	{
    		if (HTTP_Request::POST == $this->request->method())
    		{
    			try
    			{
    				$user_info = Auth::instance()->get_user();
					$propose = Model::factory('Articles')->propose_an_article(
    					$this->request->post('title'),
    					$this->request->post('content'),
    					$user_info->id);
    				$message = TRUE;
    			}
    			catch(Exception $e)
    			{
    				$message = 'error';
    			}
    		}
    		$content = View::factory('articles/propose')
    			->bind('message',$message);
    		$this->template->head ='<script type="text/javascript" src="'.URL::site("/public/js/autosize-master/jquery.autosize-min.js").'"></script>';
    		$this->template->content = $content;
    	}
    	else
    	{
    		HTTP::redirect('login');
    	}
    }    
} // Articles