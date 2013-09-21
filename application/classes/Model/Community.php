<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Community extends Model
{
    protected $_tableName = 'shoutbox';
 
    /**
     * Get all articles
     * @return array
     */
    public function get_all()
    {
        $sql = "SELECT * FROM ". $this->_tableName;
 
        return DB::query(Database::SELECT, $sql)
                   ->execute();
    }
    public function get_shouts($limit='1',$offset='0')
    {
        $query = DB::select('username','userpic','message',$this->_tableName.'.id','date')
                ->from('users')
                ->join($this->_tableName)
                ->on('users.id', '=', $this->_tableName.'.user_id')
                ->order_by($this->_tableName.'.id','DESC')
                ->limit((int)$limit)
                ->offset((int)$offset)
                ->execute()
                ->as_array(); 
 
        if($query)
            return $query;
        else
            return array();                       
    }
    public function get_new_shouts($id='')
    {
        $query = DB::select('username','userpic','message',$this->_tableName.'.id','date')
                ->from('users')
                ->where($this->_tableName.'.id','>',(int)$id)
                ->join($this->_tableName)
                ->on('users.id', '=', $this->_tableName.'.user_id')
                ->order_by($this->_tableName.'.id','DESC')
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
    public function add_shout($user_id, $message)
    {
           
        $query=DB::insert($this->_tableName, array('user_id', 'message'))
            ->values(array($user_id, $message))
            ->execute();
        if($query)
            return TRUE;
        else
            return FALSE;
    }
    public function delete_shout($message_id)
    {
        $query=DB::delete($this->_tableName)
            ->where('id','=',$message_id)
            ->execute();
        if($query)
            return TRUE;
        else
            return FALSE;
    }

}