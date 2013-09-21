<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Index extends Controller_Common {
     // Определяем шаблон по умолчанию
    public $template = 'index_template';

    // Главная страница
    public function action_index()
    {
    	
        $events = array();
        
        $content = View::factory('index')
                ->bind('events', $events)
                ->bind('matrixitems',$matrixitems)
                ->bind('articles', $articles);
        $total_items = Model::factory('Events')->count_all();
        $pagination = Pagination::factory(array(
            'total_items' => $total_items,
            )
        );

        $matrixitems=Model::factory('Events')->get_events(3);
        $articles= Model::factory('Articles')->get_page(0,10);

        $events = Model::factory('Events')->get_events(10);
        


        $this->template->title = 'Главная';
        $this->template->description = '';
        $this->template->content = $content;
    }  

} // End Page

