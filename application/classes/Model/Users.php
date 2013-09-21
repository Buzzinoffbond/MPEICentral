<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Users extends Model
{
    protected $_tableUsers = 'users';
    protected $_tableUsersInfo = 'users_info';
 
    /**
     * Get all articles
     * @return array
     */
    public function update_user_info($id,$data)
    {
        $query = DB::update($this->_tableUsersInfo)
                ->set($data)
                ->where('id', '=', $id)
                ->execute();
        return $query;
    }
    public function create_user_info($user_id)
    {
        $query = DB::insert($this->_tableUsersInfo, array('id'))
                    ->values(array(
                        (int)$user_id))
                    ->execute();
        return $query;
    }
    public function create_vk_user($user_info)
    {
        $rand_username = 'user'.strtolower(Text::random('hexdec', 5));
        $rand_password = strtolower(Text::random('alnum', 10));
        $rand_email = $rand_username.'@emptymail.ltd';

        //check if screen name have already been taken
        $check = ORM::factory('User', array('username' => $user_info['screen_name']));
        if (($check->loaded() == TRUE)) 
        {
            $user = ORM::factory('User')->create_user(array(
                    'username'          =>$rand_username,
                    'password'          =>$rand_password,
                    'password_confirm'  =>$rand_password,
                    'email'             =>$rand_email,
                    'vk_id'             =>$user_info['uid']),
                array(
                    'username',
                    'password',
                    'email',
                    'vk_id'));
        }
        else
        {
            $user = ORM::factory('User')->create_user(array(
                    'username'          =>$user_info['screen_name'],
                    'password'          =>$rand_password,
                    'password_confirm'  =>$rand_password,
                    'email'             =>$rand_email,
                    'vk_id'             =>$user_info['uid']),
                array(
                    'username',
                    'password',
                    'email',
                    'vk_id'));
        }
               
        DB::insert($this->_tableUsersInfo, array('id','real_name'))
                    ->values(array(
                        $user->pk(),
                        $user_info['first_name'].' '.$user_info['last_name']))
                    ->execute();

        $user->add('roles', ORM::factory('Role', array('name' => 'login')));

        //grab avatar
        if (!empty($user_info['photo_200']))
        {
            $local_copy = file_get_contents($user_info['photo_200']);

            $tmp_filename = strtolower(Text::random('alnum', 20)).'.jpg';
            $file  = DOCROOT.'/public/images/tmp_upload/'.$tmp_filename;

            file_put_contents($file, $local_copy);
            
            Model::factory('Image')->avatar_processing($file,$user->pk());
        }

        return $user->username;

    }
    /**
    *   Merge user vk account whith site account
    */
    public function merge_vk($user_id, $vk_id)
    {
        $vk_user_old = DB::select('id','username')
                ->from($this->_tableUsers)
                ->where('vk_id', '=', (int)$vk_id)
                ->execute()
                ->as_array();
        $vk_user_old = $vk_user_old[0];

        //update user vk_id. Now he can login whith this vk account
        DB::update($this->_tableUsers)
                ->set(array('vk_id'=>(int)$vk_id))
                ->where('id', '=', (int)$user_id)
                ->execute();

        //link old vk account comments articles and events whith site account
        DB::update('articles_comments')
            ->set(array('user_id'=>$user_id))
            ->where('user_id', '=', (int)$vk_user_old['vk_id'])
            ->execute();
        DB::update('events_comments')
            ->set(array('user_id'=>$user_id))
            ->where('user_id', '=', (int)$vk_user_old['vk_id'])
            ->execute();
        DB::update('shoutbox')
            ->set(array('user_id'=>$user_id))
            ->where('user_id', '=', (int)$vk_user_old['vk_id'])
            ->execute();
        DB::update('articles')
            ->set(array('author_id'=>$user_id))
            ->where('author_id', '=', (int)$vk_user_old['vk_id'])
            ->execute();
        DB::update('events')
            ->set(array('author_id'=>$user_id))
            ->where('author_id', '=', (int)$vk_user_old['vk_id'])
            ->execute();
        //delete old vk account
        $this->delete_user($vk_user_old['username']);

    }
    public function check_vk_connect($user_id)
    {
        $check = DB::select('vk_id')
                ->from($this->_tableUsers)
                ->where('id', '=', (int)$user_id)
                ->execute()
                ->as_array();
        if (!empty($check[0]['vk_id']))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    public function disconnect_vk($user_id)
    {
        DB::update($this->_tableUsers)
            ->set(array('vk_id'=>NULL))
            ->where('id', '=', (int)$user_id)
            ->execute();
    }
    public function get_user_info($username)
    {
        $query = DB::select('userpic', 'users.username', 'real_name', 'age', 'hometown', 'institute', 'group', 'website', 'about')
                ->from($this->_tableUsers)
                ->join($this->_tableUsersInfo)
                ->where($this->_tableUsers.'.username', '=', $username)
                ->on($this->_tableUsersInfo.'.id', '=',$this->_tableUsers.'.id' )
                ->execute()
                ->as_array(); 
        if($query)
            return $query[0];
        else
            return FALSE;
    }
    public function get_all()
    {
        $sql = "SELECT * FROM ". $this->_tableUsers;
 
        return DB::query(Database::SELECT, $sql)
                   ->execute();
    }

    /**
    *   Gets page of users
    *
    *
    */
    public function get_page_of_users($offset, $limit)
    {
        $query = DB::select('userpic', $this->_tableUsers.'.username', $this->_tableUsersInfo.'.real_name')
                ->from($this->_tableUsers)
                ->join($this->_tableUsersInfo)
                ->on($this->_tableUsersInfo.'.id', '=',$this->_tableUsers.'.id' )
                ->order_by($this->_tableUsers.'.id','DESC')
                ->limit((int)$limit)
                ->offset((int)$offset)
                ->execute();
         if($query)
            return $query;
         else
             return FALSE;
    }

    /**
    *   Count all registered users
    */
    public function count_all()
    {
        $query = DB::query(Database::SELECT, 'SELECT COUNT(*) AS "total_items" FROM '.$this->_tableUsers)
            ->execute()
            ->get('total_items', 0);


        if($query)
            return intval($query);
        else
            return FALSE;
    }
    public function update_userpic($filename,$id)
    {
        $old_avatar = DB::select('userpic')
                ->from($this->_tableUsers)
                ->where('id','=',(int)$id)
                ->execute()
                ->as_array();
        
        DB::query(Database::UPDATE, 'UPDATE '.$this->_tableUsers.' SET userpic=:filename WHERE id = :id')
            ->param(':filename',$filename)
            ->param(':id',$id)
            ->execute();
        if ($old_avatar[0]['userpic'] != 'default.jpg')
        {
            unlink(DOCROOT.'/public/images/userpics/'.$old_avatar[0]['userpic']);
        }
            
    }
    public function delete_user($username){
        $user_info = DB::select('id','userpic')
                ->from($this->_tableUsers)
                ->where('username', '=', $username)
                ->execute()
                ->as_array();
        $delete_user = DB::delete($this->_tableUsers)
                ->where('id', '=', (int)$user_info[0]['id'])
                ->execute();
        $delete_user_info = DB::delete($this->_tableUsersInfo)
                ->where('id', '=', (int)$user_info[0]['id'])
                ->execute();
        $delete_roles_users = DB::delete('roles_users')
                ->where('user_id', '=', (int)$user_info[0]['id'])
                ->execute();
        if ($user_info[0]['userpic']!='default.jpg') {
            unlink(DOCROOT.'/public/images/userpics/'.$user_info[0]['userpic']);
        }
        
        if ($delete_user) {
            DB::update('articles_comments')
                ->set(array('user_id'=>0))
                ->where('user_id', '=', $user_info[0]['id'])
                ->execute();
            DB::update('events_comments')
                ->set(array('user_id'=>0))
                ->where('user_id', '=', $user_info[0]['id'])
                ->execute();
            DB::update('shoutbox')
                ->set(array('user_id'=>0))
                ->where('user_id', '=', $user_info[0]['id'])
                ->execute();
            DB::update('articles')
                ->set(array('author_id'=>0))
                ->where('author_id', '=', $user_info[0]['id'])
                ->execute();
            DB::update('events')
                ->set(array('author_id'=>0))
                ->where('author_id', '=', $user_info[0]['id'])
                ->execute();
            return TRUE;
        }
        else {
            return FALSE;
        }
    }
} // end Users