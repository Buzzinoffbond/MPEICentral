<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Articles extends Controller_Common {
 
    public function action_index()
    {
        $articles = array();
        
        $content = View::factory('articles')
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
 
        $content = View::factory('article')
                        ->bind('article', $article)
                        ->bind('table', $table)
                        ->bind('comments', $comments);
        $article = Model::factory('Articles')->get_article_by_id($id);
 
        $comments_url = 'comments/articles/' . $id;
        $comments = Request::factory($comments_url)->execute();
 
        $this->template->content = $content;
        $this->template->title = $article['title'];
        $this->template->description = '';
    }    
} // Articles