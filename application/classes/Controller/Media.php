<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Media extends Controller_Common {

    public function action_index()
    {
    	$content = View::factory('media')
    				->bind('albums',$albums)
    				->bind('pagination',$pagination);
    	$total_items = Model::factory('Media')->count_all_albums();
        $pagination = Pagination::factory(array(
            'total_items' => $total_items,
            'items_per_page' =>'25',
            )
        );

        // Pass controller and action names explicitly to $pagination object
        $pagination->route_params(array(
        	'controller' => $this->request->controller(),
        	'action' 	 => $this->request->action()
        )); 
        // Get data
        $offset=$pagination->offset;
        $limit=$pagination->items_per_page;
        $albums = Model::factory('Media')->get_page_of_albums($offset,$limit);
        $this->template->head='<script>$(document).ready(function()
{$(".albums .album:nth-child(5n)").addClass("no-right-margin");});</script>';
        $this->template->content = $content;
    }
    public function action_album()
    {
    	$content = View::factory('album')
    				->bind('images',$images)
                    ->bind('album_info',$album_info);
        $images = Model::factory('Media')->get_images_from_album($this->request->param('id'));
        $album_info = Model::factory('Media')->get_album_info($this->request->param('id'));

        $this->template->head='<script>$(document).ready(function()
{$(".images .image:nth-child(5n)").addClass("no-right-margin");});</script>
<link href="'.URL::base().'public/js/colorbox/colorbox.css" rel="stylesheet" type="text/css">
<script src="'.URL::base().'public/js/colorbox/jquery.colorbox-min.js" type="text/javascript"></script>
<script src="'.URL::base().'public/js/colorbox/jquery.colorbox-ru.js" type="text/javascript"></script>
';
        $this->template->content = $content;
    }
}