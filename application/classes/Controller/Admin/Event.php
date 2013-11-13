<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Event extends Controller_Admin_CommonAdmin {
 
    public function action_index(){
        $events = array();
        $content = View::factory('admin/event/event-list')
                ->bind('events', $events)
                ->bind('pagination', $pagination);
        $total_items = Model::factory('Events')->count_all();
        $pagination = Pagination::factory(array(
            'total_items' => $total_items,
            )
        );
        // Pass controller and action names explicitly to $pagination object
        $pagination->route_params(array('controller' => $this->request->controller(), 'action' => $this->request->action())); 
        // Get data
        $start=$pagination->offset;
        $nums=$pagination->items_per_page;
        $events = Model::factory('Events')->get_page_by_id($start,$nums);
        $test=$pagination->offset;
        $this->template->content = $content;
    }
    public function action_new()
    {
        $content = View::factory('admin/event/new')
        ->bind('message', $message);

        if(HTTP_Request::POST == $this->request->method())
                {   
                    if (is_array($this->request->post('media')))
                    {
                        $postmedia = $this->request->post('media');
                    }
                    else
                    {
                        $postmedia = array();
                    }
                    
                    foreach ($postmedia as $mediaitem) 
                    {
                        if (!empty($mediaitem)) 
                        {
                            $media[]=$mediaitem;
                        }
                    }
                    if (!empty($media)) {
                        $media = serialize($media);
                    }
                    else
                    {
                        $media='';
                    }
                    
                    $user=Auth::instance()->get_user();
                    Model::factory('Events')->add(
                        $this->request->post('title'),
                        $this->request->post('url_title'),
                        $this->request->post('content'),
                        $this->request->post('price'),
                        $this->request->post('date'),
                        $this->request->post('start_time'),
                        $this->request->post('link'),
                        $user->id,
                        $media,
                        $_FILES['poster']);
                    
                    $message='Событие &laquo;'.HTML::chars($this->request->post('title')).'&raquo; добавлено';
                }
        $this->template->head    ='
        <script src="'.URL::site("public/js/ckeditor/ckeditor.js").'"></script>
        <script src="'.URL::site("public/js/synctranslit/jquery.synctranslit.min.js").'"></script>';
        $this->template->content = $content; 
    }
     public function action_edit()
    {
        if($this->request->param('id'))
        {
            $content = View::factory('admin/event/edit')
                    ->bind('event', $event)
                    ->bind('message', $message)
                    ->bind('embedmedia',$embedmedia);
            $id=intval($this->request->param('id'));
            if(HTTP_Request::POST == $this->request->method())
                {
                    if (is_array($this->request->post('media')))
                    {
                        $postmedia = $this->request->post('media');
                    }
                    else
                    {
                        $postmedia = array();
                    }
                    foreach ($postmedia as $mediaitem) 
                    {
                        if (!empty($mediaitem)) 
                        {
                            $media[]=$mediaitem;
                        }
                    }
                    if (!empty($media)) {
                        $media = serialize($media);
                    }
                    else
                    {
                        $media='';
                    }
                    $user=Auth::instance()->get_user();

                    $data= array(
                                'title'     =>$this->request->post('title'),
                                'url_title' =>$this->request->post('url_title'),
                                'content'   =>$this->request->post('content'),
                                'price'     =>$this->request->post('price'),
                                'link'      =>$this->request->post('link'),
                                'date'      =>$this->request->post('date'),
                                'start_time'=>$this->request->post('start_time'),
                                'media'     =>$media);
        
                    Model::factory('Events')->update($id,$data,$_FILES['poster']);
        
                    $message='Событие &laquo;'.HTML::chars($_POST['title']).'&raquo; обновлено';
                }
            $event=Model::factory('Events')->get_event_by_id($id);
            $embedmedia=unserialize($event['media']);
            unset($event['media']);
            $this->template->head    ='<script src="'.URL::site("public/js/ckeditor/ckeditor.js").'"></script>
            <script src="'.URL::site("public/js/synctranslit/jquery.synctranslit.min.js").'"></script>'; 
            $this->template->content = $content;
        }
            else{
                $events = array();
                $content = View::factory('admin/event/event-list')
                        ->bind('events', $events)
                        ->bind('pagination', $pagination);
                $total_items = Model::factory('Events')->count_all();
                $pagination = Pagination::factory(array(
                    'total_items' => $total_items,
                    )
                );
                // Pass controller and action names explicitly to $pagination object
                $pagination->route_params(array('controller' => $this->request->controller(), 'action' => $this->request->action())); 
                // Get data
                $start=$pagination->offset;
                $nums=$pagination->items_per_page;
                $events = Model::factory('Events')->get_page($start,$nums);
                $test=$pagination->offset;
                $this->template->content = $content;
            }
 
    }
        public function action_deleteposter(){
        if ($this->request->param('id'))
        {
            try
            {
                Model::factory('Events')->delete_poster($this->request->param('id'));
                $message = 'Изображение удалено';
            }
            catch(Exception $e)
            {
                $message = 'Не получилось удалить изображение, произошла ошибка.';
            }
            HTTP::redirect('/admin/event/edit/'.$this->request->param('id'));
        }
        }
        public function action_delete()
        {
            if($this->request->param('id'))
            {
                $id=intval($this->request->param('id'));
                Model::factory('Events')->delete($id);
                HTTP::redirect('/admin/event');
                
            }
            else{
                $events = array();
        
                $content = View::factory('admin/event/event-list')
                        ->bind('events', $events)
                        ->bind('pagination', $pagination);
                $total_items = Model::factory('Events')->count_all();
                $pagination = Pagination::factory(array(
                    'total_items' => $total_items,
                    )
                );
                // Pass controller and action names explicitly to $pagination object
                $pagination->route_params(array('controller' => $this->request->controller(), 'action' => $this->request->action())); 
                // Get data
                $start=$pagination->offset;
                $nums=$pagination->items_per_page;
                $events = Model::factory('Events')->get_page($start,$nums);
                $test=$pagination->offset;
                $this->template->content = $content;
            }
        }
    

    public function action_proposed(){
        if($this->request->param('id'))
        {
            $content = View::factory('admin/event/proposed')
                    ->bind('event', $event)
                    ->bind('message', $message);
            $id=intval($this->request->param('id'));
            if($this->request->post('submit'))
                {
                    $url_title = URL::title(HelpingStuff::rusToLat($this->request->post('url_title')), '_');
                    
                    foreach ($this->request->post('media') as $mediaitem) 
                    {
                        if (!empty($mediaitem)) 
                        {
                            $media[]=$mediaitem;
                        }
                    }
                    if (!empty($media)) {
                        $media = serialize($media);
                    }
                    else
                    {
                        $media='';
                    } 
                    
                    Model::factory('Events')->add(
                        $this->request->post('title'),
                        $this->request->post('url_title'),
                        $this->request->post('content'),
                        $this->request->post('price'),
                        $this->request->post('date'),
                        $this->request->post('start_time'),
                        $this->request->post('link'),
                        $this->request->post('author_id'),
                        $media,
                        $_FILES['poster']);
        
                    $message='Событие &laquo;'.HTML::chars($this->request->post('title')).'&raquo; опубликовано';
                }
            $this->template->head='<script src="'.URL::site("public/js/ckeditor/ckeditor.js").'"></script>
            <script src="'.URL::site("/public/js/synctranslit/jquery.synctranslit.min.js").'"></script>
            <script type="text/javascript" src="'.URL::site("/public/js/autosize-master/jquery.autosize-min.js").'"></script>';
            $event=Model::factory('Events')->get_event_by_id($id,'events_proposed');
        }
        else
        {
            if($this->request->query('delete'))
            {
                try
                {
                    $title = Model::factory('Events')->delete($this->request->query('delete'),'events_proposed');
                    $message='Событие '.HTML::chars($title).' удалено';
                }
                catch(Exception $e)
                {
                    $message = 'Произошла ошибка';
                }
            }
            $events = array();
            $content = View::factory('admin/event/proposed-event-list')
                ->bind('events', $events)
                ->bind('pagination', $pagination)
                ->bind('message',$message);
            $total_items = Model::factory('Events')->count_all('events_proposed');
            $pagination = Pagination::factory(array(
                'total_items' => $total_items,
                'items_per_page'=> 30,
                ));
            $pagination->route_params(array('controller' => $this->request->controller(), 'action' => $this->request->action())); 
            $offset=$pagination->offset;
            $limit=$pagination->items_per_page;
            $events = Model::factory('Events')->get_page_by_id($offset,$limit,'events_proposed');
        }
        $this->template->content = $content;
    }
} // End Page

