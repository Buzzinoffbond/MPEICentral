<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Events extends Controller_Common {
	public function action_index()
    {
        $events = array();
        
        $content = View::factory('events/events')
                ->bind('events', $events)
                ->bind('pagination', $pagination);
        $total_items = Model::factory('Events')->count_upcoming();
        $pagination = Pagination::factory(array(
            'total_items' => $total_items
            )
        );
        // Pass controller and action names explicitly to $pagination object
        $pagination->route_params(array('controller' => $this->request->controller(), 'action' => $this->request->action())); 
        // Get data
        $start=$pagination->offset;
        $nums=$pagination->items_per_page;
        $events = Model::factory('Events')->get_page_by_date($start,$nums);
        $this->template->content = $content;
        $this->template->title = 'События';
        $this->template->description = 'События МЭИ';
    }

    public function action_event()
    {
        if($this->request->param('id'))
        {
            $user_id = Auth::instance()->get_user('id');
            if ($this->request->post('attend'))
            {
                
                Model::factory('Events')->attend($this->request->param('id'),$user_id->id);
            }
            $id = intval($this->request->param('id'));
            $content = View::factory('events/event')
                       ->bind('event', $event)
                       ->bind('comments', $comments)
                       ->bind('embedmedia',$embedmedia)
                       ->bind('attended',$attended)
                       ->bind('i_will_go',$i_will_go);
            $event = Model::factory('Events')->get_event_by_id($id);
            if (empty($event))
            {
                throw new HTTP_Exception_404('Такого события не существует.');
            }
            $embedmedia=HelpingStuff::embedmedia($event['media']);
            unset($event['media']);
            $comments_url = 'comments/events/' . $id;   
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
            $i_will_go = Model::factory('Events')->check_events_attend($this->request->param('id'),$user_id->id);
            $attended = Model::factory('Events')->get_attended($this->request->param('id'));
            $this->template->head    ='
            <link rel="stylesheet" href="'.URL::site("public/js/showcase/css/style.css").'" />
            <script type="text/javascript" src="'.URL::site("public/js/showcase/jquery.aw-showcase.js").'"></script>
                <script type="text/javascript">
                $(document).ready(function()
                {
                    $("#showcase").awShowcase(
                    {
                        fit_to_parent:     true,
                        auto:              false,
                        transition:        "fade"
                    });
                });
                </script>
            ';
            $this->template->content = $content;
            $this->template->title = $event['title'];
            $this->template->description = '';
        }
        else
        {
            HTTP::redirect('events');
        }

    }
    public function action_calendar()
    {
        $content = View::factory('events/calendar')
        ->bind('calendar',$calendar)
        ->bind('events',$events)
        ->bind('nextdate', $nextdate)
        ->bind('prevdate',$prevdate);

        if(($this->request->param('year')) and ($this->request->param('month')))
            {   
                $year = (int)$this->request->param('year');
                $month = (int)$this->request->param('month');
            }
        else    // иначе выводить текущие месяц и год
            {
                $month = date("m", mktime(0,0,0,date('m'),1,date('Y')));
                $year = date("Y", mktime(0,0,0,date('m'),1,date('Y')));
            }
        $skip = date("N", mktime(0,0,0,$month,1,$year)); // узнаем номер дня недели с которого начинается месяц
        $skip=$skip - 1;
        $daysInMonth = date("t", mktime(0,0,0,$month,1,$year)); // узнаем число дней в месяце
        
        //определяем будущие и предыдущие даты для навигации
        $nextdate=date('o\/m',mktime(0, 0, 0, $month+1, 1, $year));
        $prevdate=date('o\/m',mktime(0, 0, 0, $month-1, 1, $year));
        if($month=='01'){$monthname='Январь';}
        if($month=='02'){$monthname='Февраль';}
        if($month=='03'){$monthname='Март';}
        if($month=='04'){$monthname='Апрель';}
        if($month=='05'){$monthname='Май';}
        if($month=='06'){$monthname='Июнь';}
        if($month=='07'){$monthname='Июль';}
        if($month=='08'){$monthname='Август';}
        if($month=='09'){$monthname='Сентябрь';}
        if($month=='10'){$monthname='Октябрь';}
        if($month=='11'){$monthname='Ноябрь';}
        if($month=='12'){$monthname='Декабрь';}


        $calendar= array(
            'year' => $year,
            'month'=> $month,
            'daysInMonth'=> $daysInMonth,
            'skip'=> $skip,
            'monthname'=>$monthname,
             );
        
        for ($day=1; $day <(int)$daysInMonth+1;  $day++) { 
            $date=date('o\-m\-d',mktime(0, 0, 0, $month, $day, $year));

            $query = Model::factory('Events')->get_event_by_date($date);
            
            if($query!=false){
                foreach($query as $item) {
                            
            $events[$day][$item['id']] = $item;}
        }
        }
        $this->template->title = 'Календарь';
        $this->template->description = 'Календарь событий МЭИ';
        $this->template->content = $content;
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
                    $propose = Model::factory('Events')->propose_an_event(
                        $this->request->post('title'),
                        $this->request->post('date'),
                        $this->request->post('content'),
                        $user_info->id);

                    $message = TRUE;
                }
                catch(Exception $e)
                {
                    $message = 'error';
                }
            }
            $content = View::factory('events/propose')
                ->bind('message',$message);
            $this->template->head ='<script type="text/javascript" src="'.URL::site("/public/js/autosize-master/jquery.autosize-min.js").'"></script>';
            $this->template->content = $content;
        }
        else
        {
            HTTP::redirect('login');
        }
    }    

}//end Events