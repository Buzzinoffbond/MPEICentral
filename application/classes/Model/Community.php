<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Community extends Model
{
    protected $_tableShoutbox = 'shoutbox';
 
    /**
     * Get all articles
     * @return array
     */
    public function get_all()
    {
        $sql = "SELECT * FROM ". $this->_tableShoutbox;
 
        return DB::query(Database::SELECT, $sql)
                   ->execute();
    }
    public function get_shouts($limit='1',$offset='0')
    {
        $subPMsg = DB::select('subPMsg.id')->from(array($this->_tableShoutbox,'subPMsg'))->where('subPMsg.pid','=',0)->order_by('subPMsg.id', 'DESC');
        $childMsg = DB::select(array('childMsg.id','id'),'childMsg.pid','username','userpic','childMsg.message','childMsg.date')
        ->from(array($this->_tableShoutbox,'childMsg'))
        ->join('users')
        ->on('childMsg.user_id','=','users.id')
        ->where('childMsg.pid','IN', $subPMsg);
        $query = DB::select(array('parentMsg.id','id'),'parentMsg.pid','username','userpic','parentMsg.message','parentMsg.date')
        ->from(array($this->_tableShoutbox,'parentMsg'))
        ->join('users')
        ->on('parentMsg.user_id','=','users.id')
        ->where('parentMsg.pid','=','0')
        ->limit((int)$limit)
        ->offset((int)$offset)
        ->union($childMsg)
        ->order_by('id', 'DESC')
        ->execute()
        ->as_array();
 
        if($query)
            return $query;
        else
            return array();                       
    }
    public function get_new_shouts($id)
    {
        $query = DB::select('username','userpic','message',$this->_tableShoutbox.'.id',$this->_tableShoutbox.'.pid','date')
                ->from('users')
                ->where($this->_tableShoutbox.'.id','>',(int)$id)
                ->join($this->_tableShoutbox)
                ->on('users.id', '=', $this->_tableShoutbox.'.user_id')
                ->order_by($this->_tableShoutbox.'.id','DESC')
                ->execute()
                ->as_array();
        if(!empty($id))
        {
            if($query)
                return $query;
            else
                return array();
        }    
        else
            return "false";                       
    }
    public function add_shout($user_id, $message, $pid=0)
    {  
        $query=DB::insert($this->_tableShoutbox, array('user_id','message','pid'))
            ->values(array((int)$user_id, $message, (int)$pid ))
            ->execute();
        if($query)
            return TRUE;
        else
            return FALSE;
    }
    public function delete_shout($message_id)
    {
        $query=DB::delete($this->_tableShoutbox)
            ->where('id','=',$message_id)
            ->execute();
        if($query)
            return TRUE;
        else
            return FALSE;
    }

}