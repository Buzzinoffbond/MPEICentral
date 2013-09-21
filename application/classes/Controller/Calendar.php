<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Calendar extends Controller_Common {

    public function action_index()
    {
        $content = View::factory('calendar')
        ->bind('calendar',$calendar)
        ->bind('events',$events)
        ->bind('nextdate', $nextdate)
        ->bind('prevdate',$prevdate);

        if(($this->request->param('year')) and ($this->request->param('month')))
			{	
				$year = (int)$this->request->param('year');
				$month = (int)$this->request->param('month');
			}
		else	// иначе выводить текущие месяц и год
			{
				$month = date("m", mktime(0,0,0,date('m'),1,date('Y')));
				$year = date("Y", mktime(0,0,0,date('m'),1,date('Y')));
			}
		$skip = date("N", mktime(0,0,0,$month,1,$year)); // узнаем номер дня недели с которого начинается месяц
		$skip=$skip - 1;
		$daysInMonth = date("t", mktime(0,0,0,$month,1,$year));	// узнаем число дней в месяце
		
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
 
} // End Page

