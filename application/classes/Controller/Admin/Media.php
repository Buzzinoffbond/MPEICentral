<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Media extends Controller_Admin_CommonAdmin {
    
    public function action_index(){
        $content = View::factory('admin/media/album-list')
                    ->bind('albums',$albums)
                    ->bind('pagination',$pagination)
                    ->bind('messages',$messages);
        $total_items = Model::factory('Media')->count_all_albums();
        $pagination = Pagination::factory(array(
            'total_items' => $total_items,
            'items_per_page'=>'50',
            )
        );
        $pagination->route_params(array(
            'controller' => $this->request->controller(),
            'action'     => $this->request->action()
        ));
        $offset=$pagination->offset;
        $limit=$pagination->items_per_page;
        $albums = Model::factory('Media')->get_page_of_albums($offset,$limit);
        $this->template->head='<script>$(document).ready(function()
{$(".albums .album:nth-child(5n)").addClass("no-right-margin");});</script>';
        $this->template->content = $content;
    }
    public function action_addalbum(){
        $content = View::factory('admin/media/addalbum')
                                ->bind('arr',$arr)
                                ->bind('messages',$messages);
        $arr=array();
        if($this->request->post('submit'))
        {
            $newalbum=Model::factory('Media')->add_album(
                        $this->request->post('title'),
                        $this->request->post('description'));
            HTTP::redirect(URL::site("admin/media/edit/".$newalbum['0']));
        }
        
        $this->template->content = $content;
    }
    public function action_edit()
    {
        if($this->request->param('id'))
        {        
            if ($this->request->post('update_info'))
            {     
                $result = Model::factory('Media')->update_album(
                    $this->request->param('id'),
                    $this->request->post('title'),
                    $this->request->post('description'));

                $sort_photos = array();
                parse_str($this->request->post('sort_photos'), $sort_photos);
                if (!empty($sort_photos)) 
                {
                    $sort = Model::factory('Media')->sort_photos($sort_photos);
                }
            }

            $content = View::factory('admin/media/edit')
                                ->bind('messages',$messages)
                                ->bind('images',$images)
                                ->bind('album_info',$album_info);

            $images = Model::factory('Media')->get_images_from_album($this->request->param('id'));
            $album_info = Model::factory('Media')->get_album_info($this->request->param('id'));

            $this->template->head ='<script type="text/javascript" src="'.URL::site("public/js/autosize-master/jquery.autosize-min.js").'"></script>
            <script type="text/javascript" src="'.URL::site("public/js/plupload/plupload.full.min.js").'"></script>
            <script type="text/javascript" src="'.URL::site("public/js/plupload/jquery.plupload.queue/jquery.plupload.queue.min.js").'"></script>
            <script type="text/javascript" src="'.URL::site("public/js/plupload/i18n/ru.js").'"></script>
            <link href="'.URL::site("public/js/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css").'" rel="stylesheet" type="text/css">';
            $this->template->content = $content;
        }
        else
        {
            HTTP::redirect('admin/media/');
        }
    }
    public function action_delete()
    {
        if($this->request->param('id'))
        {
            $id = $this->request->param('id');
            Model::factory('Media')->delete_album($id);
            HTTP::redirect('admin/media/');
        }
    }

    public function action_setcover()
    {
        if($this->request->param('id') AND $this->request->query('photo_id'))
        {
            Model::factory('Media')->set_album_cover($this->request->param('id'),$this->request->query('photo_id'));
            HTTP::redirect('admin/media/edit/'.$this->request->param('id'));
        }
    }

    public function action_deletephoto()
    {
        if($this->request->param('id') AND $this->request->query('photo_id'))
        {
            Model::factory('Media')->delete_photo($this->request->query('photo_id'));
            HTTP::redirect('admin/media/edit/'.$this->request->param('id'));
        }
    }
}

