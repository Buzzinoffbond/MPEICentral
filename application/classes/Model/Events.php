<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Events extends Model
{
    protected $_tableEvents = 'events';
    protected $_tableEventsProposed = 'events_proposed';
    protected $_tableEventsComments = 'events_comments';
    protected $_tableEventsAttendance = 'events_attendance';
    protected $_tableUsers = 'users';
    protected $_tableUsersInfo = 'users_info';
 
    /**
     * Get all events
     * @return array
     */
    public function get_all()
    {
        $sql = "SELECT * FROM ". $this->_tableEvents;
 
        return DB::query(Database::SELECT, $sql)
                   ->execute();
    }
    public function get_events($limit='1')
    {
        $query = DB::select('*')
                ->from($this->_tableEvents)
                ->where('date','>=',date("o\-m\-d", mktime(0,0,0,date('m'),date('d'),date('Y'))) )
                ->order_by('date','ASC')
                ->limit((int)$limit)
                ->execute()
                ->as_array();
        if($query)
            return $query;
        else
            return array();

    }
    public function get_event_by_id($id = '',$table = NULL)
    {
        if (empty($table))
        {
            $table = $this->_tableEvents;
        }
        $result = DB::select($table.'.*','username')
                ->from($table)
                ->join('users')
                ->where($table.'.id', '=', $id)
                ->on($table.'.author_id', '=','users.id' )
                ->execute()
                ->as_array();
        if($result)
            return $result[0];
        else
            return FALSE;
    }
    public function get_event_by_date($date)
    {
        $sql = "SELECT * FROM ". $this->_tableEvents ." WHERE date = :date";
 
        return DB::query(Database::SELECT, $sql, FALSE)
                         ->param(':date', $date)
                         ->execute();

    }
    public function count_all()
    {
        if (empty($table))
        {
            $table = $this->_tableEvents;
        }
        $query = DB::query(Database::SELECT, 'SELECT COUNT(*) AS "total_items" FROM '.$table)
            ->execute()
            ->get('total_items', 0);


        if($query)
            return intval($query);
        else
            return FALSE;
    }
    /*
    *   count upcoming events
    **/
    public function count_upcoming()
    {
        if (empty($table))
        {
            $table = $this->_tableEvents;
        }
        $query = DB::query(Database::SELECT, 'SELECT COUNT(*) AS "total_items" FROM '.$table.' WHERE date>=NOW()')
            ->execute()
            ->get('total_items', 0);


        if($query)
            return intval($query);
        else
            return FALSE;
    }
    public function get_page_by_id($start,$nums,$table = NULL)
    {
        if (empty($table))
        {
            $table = $this->_tableEvents;
        }
        $query = DB::select($table.'.*','username')
                ->from($table)
                ->join('users')
                ->on($table.'.author_id', '=','users.id' )
                ->order_by('id','DESC')
                ->limit((int)$nums)
                ->offset((int)$start)
                ->execute();
         if($query)
            return $query;
         else
             return FALSE;
    }
    public function get_page_by_date($start,$nums,$start_date = NULL, $table = NULL)
    {
        if (empty($start_date)) {
            $start_date = date("Y-m-d");
        }
        if (empty($table))
        {
            $table = $this->_tableEvents;
        }
        $query = DB::select($table.'.*','username')
                ->from($table)
                ->join('users')
                ->on($table.'.author_id', '=','users.id' )
                ->where('date','>=',$start_date)
                ->order_by('date','ASC')
                ->limit((int)$nums)
                ->offset((int)$start)
                ->execute()
                ->as_array();
         if($query)
            return $query;
         else
             return FALSE;
    }
    public function update($id,$data,$poster)
    {
            $query = DB::update($this->_tableEvents)
                    ->set($data)
                    ->where('id', '=', (int)$id)
                    ->execute();
        if (!empty($poster))
        {
            $this->set_poster($id, $poster);
        }    
    }
    public function add($title,$url_title,$content,$price,$date,$start_time,$link,$author_id,$media,$poster)
    {
        $query = DB::insert($this->_tableEvents, array('title','url_title','content','price','link','date','start_time','author_id','media'))
                    ->values(array(
                        $title,
                        $url_title,
                        $content,
                        $price,
                        $link,
                        $date,
                        $start_time,
                        $author_id,
                        $media))
                    ->execute();
        if (!empty($poster))
        {
            $this->set_poster($query[0], $poster);
        }
    }
    /** 
    *   Upload and processes event poster
    *
    *   @param $event_id    int     id of an event. Also used for picture filename.  
    *   @param $image       file    image info
    **/
    public function set_poster($event_id, $image)
    {
        if (
                ! Upload::valid($image) OR
                ! Upload::not_empty($image) OR
                ! Upload::type($image, array('jpg', 'jpeg', 'png', 'gif')))
            {
                return FALSE;
            }
        $curdate = date('Y/m');
        $directory = DOCROOT.'public/images/events/'.$curdate;
        $posterfullpath  = $directory.'/'.$event_id.'.jpg';
        $postershortpath = 'public/images/events/'.$curdate.'/'.$event_id.'.jpg';    
        
        Filesystem::make_path($directory);
        if ($file = Upload::save($image, NULL, $directory))
        {
            $picture = Image::factory($file);
            if ($picture->height > 500) 
            {
                $picture->resize(NULL, 500);
            }
            $picture->save($posterfullpath, 85);
            unlink($file);


            $query = DB::update($this->_tableEvents)
                        ->set(array('poster'=>$postershortpath))
                        ->where('id', '=', (int)$event_id)
                        ->execute();
        }
    }
    public function delete($id,$table = NULL)
    {
        if (empty($table))
        {
            $table = $this->_tableEvents;
        }
        $poster = DB::select('poster')
                ->from($table)
                ->where('id', '=', (int)$id)
                ->execute()
                ->as_array();
        
        
        DB::delete($this->_tableEventsComments)
        ->where('event_id','=',(int)$id)
        ->execute();
        
        $query = DB::delete($table)
                    ->where('id', '=', (int)$id)
                    ->execute();
        if(!empty($poster[0]['poster']))
        {
            unlink($poster[0]['poster']);
        }
    }
    public function delete_poster($event_id){

        $poster = DB::select('poster')
                ->from($this->_tableEvents)
                ->where('id', '=', (int)$event_id)
                ->execute()
                ->as_array();

        if (unlink($poster[0]['poster']) and (DB::update($this->_tableEvents)->set(array('poster'=>''))->where('id', '=', (int)$event_id)->execute()))
        {
            return TRUE;
        }
        else 
        { 
            return FALSE;
        }
    }

    /**
    *   Propose an event
    */
    public function propose_an_event($title,$date,$content,$author_id){
        if (!empty($title))
        {
            $query = DB::insert($this->_tableEventsProposed, array('title','content','date','author_id'))
                ->values(array(
                    $title,
                    $content,
                    $date,
                    $author_id))
                ->execute();
            if ($query)
                return TRUE;
            else
            {
                throw new Exception('Произошла ошибка');
            }
        }
        else
        {
            throw new Exception('Название не может быть пустыми');
        }
    }
    public function get_attended($event_id){
        $attended = DB::select($this->_tableUsers.'.*')
            ->from($this->_tableEventsAttendance)
            ->where('event_id','=',$event_id)
            ->join($this->_tableUsers)
            ->on($this->_tableUsers.'.id','=','user_id')
            ->execute();
        if($attended)
        {
            return $attended;
        }
        return FALSE;
    }
    public function check_events_attend($event_id,$user_id){
        $attend = DB::select('*')
            ->from($this->_tableEventsAttendance)
            ->where('event_id','=',(int)$event_id)
            ->where('user_id','=',(int)$user_id)
            ->execute()
            ->current();
        if (!empty($attend))
        {
            return TRUE;
        }
        return FALSE;
    }
    /**
    *   set account attend event. if it has already set, unset
    *
    */
    public function attend($event_id,$user_id)
    {
        if ($this->check_events_attend($event_id,$user_id))
        {
            $unset = DB::delete($this->_tableEventsAttendance)
            ->where('user_id','=',(int)$user_id)
            ->execute();
        }
        else
        {
           $set = DB::insert($this->_tableEventsAttendance, array('user_id','event_id'))
            ->values(array(
                (int)$user_id,
                (int)$event_id))
            ->execute(); 
        }
    }
}