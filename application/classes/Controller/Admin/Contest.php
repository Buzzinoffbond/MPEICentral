<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Contest extends Controller_Admin_CommonAdmin {

	public function action_index(){
        $contests = array();
        $content = View::factory('admin/contest/contest-list')
            ->bind('contests', $contests)
            ->bind('pagination', $pagination);
        $total_items = Model::factory('Contest')->count_all();
        $pagination = Pagination::factory(array(
            'total_items' => $total_items,
            'items_per_page'=> 30,
            ));
        $pagination->route_params(array('controller' => $this->request->controller(), 'action' => $this->request->action())); 
        $offset=$pagination->offset;
        $limit=$pagination->items_per_page;
        $contests = Model::factory('Contest')->get_page($offset,$limit);
        $this->template->content = $content;
    }
    public function action_new()
    {
    	$content = View::factory('admin/contest/new')
        ->bind('message', $message);

        if(HTTP_Request::POST == $this->request->method())
        {
        	try
        	{
        		$url_title = URL::title(HelpingStuff::rusToLat($this->request->post('url_title')), '_');
            	$result = Model::factory('Contest')->add(
                	$this->request->post('title'),
                	$url_title
            	);
            	HTTP::redirect('admin/contest/edit/'.$result[0]);
        	}
        	catch(Exceptions $e)
        	{
        		$message[]='Произошла ошибка.';
        	}
        }
        $this->template->head    ='<script src="'.URL::site("public/js/synctranslit/jquery.synctranslit.min.js").'"></script>';
        $this->template->content = $content;
    }
    public function action_edit()
    {
    	if ($this->request->param('id'))
    	{

    	$content = View::factory('admin/contest/edit')
        ->bind('message', $message)
        ->bind('contest_info', $contest_info)
        ->bind('competitors', $competitors);
        if($this->request->post('submit_contest_info'))
        {
        	try
        	{
            	$result = Model::factory('Contest')->update(
            		$this->request->param('id'),
                	$this->request->post('title'),
                	$this->request->post('url_title'),
                	$this->request->post('description'),
                	$_FILES['cover']);
        	}
        	catch(Exceptions $e)
        	{
        		$message[]='Произошла ошибка.';
        	}
        }
        if($this->request->post('submit_competitors_data'))
        {
        	try
        	{
            	$result = Model::factory('Contest')->addcompetitor(
                	$this->request->param('id'),
                    $this->request->post('name'));
                HTTP::redirect('/admin/contest/competitor/'.$result[0]);
        	}
        	catch(Exceptions $e)
        	{
        		$message[]='Произошла ошибка.';
        	}
        }
        if($this->request->query('delete_contest'))
        {
        	try
        	{
            	Model::factory('Contest')->delete_contest($this->request->param('id'));
                HTTP::redirect('/admin/contest/');
        	}
        	catch(Exceptions $e)
        	{
        		$message[]='Произошла ошибка.';
        	}
        }
        if($this->request->query('toggle_active'))
        {
        	try
        	{
            	Model::factory('Contest')->toggle_active($this->request->param('id'));
        	}
        	catch(Exceptions $e)
        	{
        		$message[]='Произошла ошибка.';
        	}
        }
        $contest_info = Model::factory('Contest')->get_info($this->request->param('id'));
        if(empty($contest_info))
        {
        	HTTP::redirect('/admin/contest/');
        }
        $competitors = Model::factory('Contest')->get_competitors($this->request->param('id'));
        $this->template->head    ='<script src="'.URL::site("public/js/ckeditor/ckeditor.js").'"></script>
        <script src="'.URL::site("public/js/synctranslit/jquery.synctranslit.min.js").'"></script>
        <script type="text/javascript" src="'.URL::site("public/js/autosize-master/jquery.autosize-min.js").'"></script>';
        $this->template->content = $content;	
    	}
    	else
    	{
    		HTTP::redirect('/admin/contest/');
    	}
    }
    public function action_competitor()
    {
        $content = View::factory('admin/contest/competitor')
        ->bind('message', $message)
        ->bind('competitor', $competitor)
        ->bind('images',$images);
        if($this->request->post('update_data'))
        {
            try
            {
                $result = Model::factory('Contest')->competitor_data(
                    $this->request->param('id'),
                    $this->request->post('name'),
                    $this->request->post('url_title'),
                    $this->request->post('description'));

                Model::factory('Contest')->sort_competitor_photos($this->request->param('id'),$this->request->post('sort_photos'));
            }
            catch(Exceptions $e)
            {
                $message[]='Произошла ошибка.';
            }
        }
        if($this->request->query('delete_photo'))
        {
            try
            {
                Model::factory('Contest')->delete_photo($this->request->query('delete_photo'));
                HTTP::redirect('admin/contest/competitor/'.$this->request->param('id'));
            }
            catch(Exceptions $e)
            {
                $message[]='Не удалось удалить фотографию.';
            }
        }
        if($this->request->query('make_default'))
        {
            try
            {
                Model::factory('Contest')->default_comp_image($this->request->param('id'),$this->request->query('make_default'));
                HTTP::redirect('admin/contest/competitor/'.$this->request->param('id'));
            }
            catch(Exceptions $e)
            {
                $message[]='Не удалось сделать фотографию основной.';
            }
        }
        $competitor = Model::factory('Contest')->get_competitor($this->request->param('id'));
        if(empty($competitor))
        {
        	HTTP::redirect('/admin/contest/');
        }
        $images = Model::factory('Contest')->get_competitor_photos($this->request->param('id'));
        $this->template->head    ='<script src="'.URL::site("public/js/ckeditor/ckeditor.js").'"></script>
        <script src="'.URL::site("public/js/synctranslit/jquery.synctranslit.min.js").'"></script>
        <script type="text/javascript" src="'.URL::site("public/js/autosize-master/jquery.autosize-min.js").'"></script>
        <script type="text/javascript" src="'.URL::site("public/js/plupload/plupload.full.min.js").'"></script>
            <script type="text/javascript" src="'.URL::site("public/js/plupload/jquery.plupload.queue/jquery.plupload.queue.min.js").'"></script>
            <script type="text/javascript" src="'.URL::site("public/js/plupload/i18n/ru.js").'"></script>
            <link href="'.URL::site("public/js/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css").'" rel="stylesheet" type="text/css">';
        $this->template->content = $content;
    }
}