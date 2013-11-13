<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Comments extends Model
{
    protected $_tableComments = 'articles_comments';
 
    /**
     * Get comments for article
     * @return array
     */
    public function get_comments($section,$item_id)
    {
        $table=$section."_comments";
        $section_row = substr($section, 0, -1)."_id";

        $query = DB::select('username', 'message','userpic','date')
                ->from('users')
                ->join($table)
                ->where($section_row, '=', $item_id)
                ->on('users.id', '=', $table.'.user_id')
                ->execute()
                ->as_array(); 
 
        if($query)
            return $query;
        else
            return array();                       
    }
 
    /**
     * Create new comment
     */
    public function create_comment($section,$item_id, $user_id, $message)
    {
        $table=$section."_comments";
        $section_row = substr($section, 0, -1)."_id";
        
        DB::insert($table, array($section_row, 'user_id', 'message'))
            ->values(array($item_id,(int)$user_id, $message))
            ->execute();                       
    }
}