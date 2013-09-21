<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Article extends Controller_Admin_CommonAdmin {
 
    public function action_index(){
        $articles = array();
        $content = View::factory('admin/article/article-list')
            ->bind('articles', $articles)
            ->bind('pagination', $pagination);
        $total_items = Model::factory('Articles')->count_all();
        $pagination = Pagination::factory(array(
            'total_items' => $total_items,
            'items_per_page'=> 30,
            ));
        $pagination->route_params(array('controller' => $this->request->controller(), 'action' => $this->request->action())); 
        $offset=$pagination->offset;
        $limit=$pagination->items_per_page;
        $articles = Model::factory('Articles')->get_page($offset,$limit);
        $this->template->content = $content;
    }
    public function action_new()
    {
        $content = View::factory('admin/article/new')
        ->bind('message', $message);

        if($this->request->post())
                {
                    $user=Auth::instance()->get_user();
                    $url_title = URL::title(HelpingStuff::rusToLat($this->request->post('url_title')), '_');
                    Model::factory('Articles')->add(
                        $this->request->post('title'),
                        $url_title,
                        $this->request->post('content'),
                        $this->request->post('content_short'),
                        $user->id,
                        $_FILES['kdpv'],
                        $this->request->post('kdpv_description')
                        );
                    
                    $message='Статья &laquo;'.HTML::chars($_POST['title']).'&raquo; добавлена';
                }
        $this->template->head    ='<script src="'.URL::site("public/js/ckeditor/ckeditor.js").'"></script>
        <script src="'.URL::site("public/js/synctranslit/jquery.synctranslit.min.js").'"></script>
        <script type="text/javascript" src="'.URL::site("public/js/autosize-master/jquery.autosize-min.js").'"></script>';
        $this->template->content = $content; 
    }
     public function action_edit()
    {            
     
        if($this->request->param('id'))
        {
            $content = View::factory('admin/article/edit')
                    ->bind('article', $article)
                    ->bind('message', $message);
            $id=intval($this->request->param('id'));
            $url_title = URL::title(HelpingStuff::rusToLat($this->request->post('url_title')), '_');
            if(isset($_POST['submit']))
                {
                    $data= array(
                                'title'             =>$this->request->post('title'),
                                'url_title'         =>$url_title,
                                'content'           =>$this->request->post('content'),
                                'content_short'     =>$this->request->post('content_short'),
                                'kdpv_description'  =>$this->request->post('kdpv_description'));
        
                    Model::factory('Articles')->update($id,$data,$_FILES['kdpv']);
        
                    $message='Статья &laquo;'.HTML::chars($_POST['title']).'&raquo; обновлена';
                }
            $this->template->head    ='<script src="'.URL::site("public/js/ckeditor/ckeditor.js").'"></script>
            <script src="'.URL::site("/public/js/synctranslit/jquery.synctranslit.min.js").'"></script>
            <script type="text/javascript" src="'.URL::site("/public/js/autosize-master/jquery.autosize-min.js").'"></script>';
            $article=Model::factory('Articles')->get_article_by_id($id);
        }
        else
        {
            $articles = array();
            $content = View::factory('admin/article/article-list')
                ->bind('articles', $articles)
                ->bind('pagination', $pagination);
            $total_items = Model::factory('Articles')->count_all();
            $pagination = Pagination::factory(array(
                'total_items' => $total_items,
                ));
            $pagination->route_params(array('controller' => $this->request->controller(), 'action' => $this->request->action())); 
            $offset=$pagination->offset;
            $limit=$pagination->items_per_page;
            $articles = Model::factory('Articles')->get_page($offset,$limit);
        }
        $this->template->content = $content;
    } 

    public function action_deletekdpv(){
        if ($this->request->param('id'))
        {
            try
            {
                Model::factory('Articles')->delete_kdpv($this->request->param('id'));
                $message = 'Изображение удалено';
            }
            catch(Exception $e)
            {
                $message = 'Не получилось удалить изображение, произошла ошибка.';
            }
            HTTP::redirect('/admin/article/edit/'.$this->request->param('id'));
        }
        

    }
        public function action_delete()
        {
            if($this->request->param('id'))
            {
                $id=intval($this->request->param('id'));
                $title=Model::factory('Articles')->delete($id);
                $message='Статья &laquo;'.HTML::chars($title).'&raquo; удалена';
            }
            $articles = array();
            $content = View::factory('admin/article/article-list')
                ->bind('articles', $articles)
                ->bind('pagination', $pagination)
                ->bind('message',$message);
            $total_items = Model::factory('Articles')->count_all();
            $pagination = Pagination::factory(array(
                'total_items' => $total_items,
                ));
            $pagination->route_params(array('controller' => $this->request->controller(), 'action' => $this->request->action())); 
            $offset=$pagination->offset;
            $limit=$pagination->items_per_page;
            $articles = Model::factory('Articles')->get_page($offset,$limit);
            $this->template->content = $content;
        }

} // End Page

