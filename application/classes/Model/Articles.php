<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Articles extends Model
{
    protected $_tableArticles = 'articles';
    protected $_tableProposedArticles = 'proposed_articles';
    /**
     * Get all articles
     * @return array
     */
    public function get_all()
    {
        $sql = "SELECT * FROM ". $this->_tableArticles." ORDER BY id DESC";
 
        return DB::query(Database::SELECT, $sql)
                   ->execute();
    }
    public function get_article_by_id($id,$table = NULL)
    {
        if (empty($table))
        {
            $table = $this->_tableArticles;
        }
        $query = DB::select($table.'.*','username')
                ->from($table)
                ->join('users')
                ->where($table.'.id', '=', $id)
                ->on($table.'.author_id', '=','users.id' )
                ->execute()
                ->as_array();
        if($query)
            return $query[0];
        else
            return FALSE;
    }
    public function count_all($table = NULL)
    {
        if (empty($table))
        {
            $table = $this->_tableArticles;
        }
        $query = DB::query(Database::SELECT, 'SELECT COUNT(*) AS "total_items" FROM '.$table)
            ->execute()
            ->get('total_items', 0);


        if($query)
            return intval($query);
        else
            return FALSE;
    }
    public function get_page($start,$nums,$table = NULL)
    {
        if (empty($table))
        {
            $table = $this->_tableArticles;
        }
        $query = DB::select($table.'.*','username')
                ->from($table)
                ->join('users')
                ->on($table.'.author_id', '=','users.id' )
                ->order_by('id','DESC')
                ->limit((int)$nums)
                ->offset((int)$start)
                ->execute()
                ->as_array();
         if($query)
            return $query;
         else
             return FALSE;
    }
    /**
    *   Update an existing article
    *
    *
    *
    */
    public function update($id,$data,$kdpv)
    {
        $query = DB::update($this->_tableArticles)
                ->set($data)
                ->where('id', '=', $id)
                ->execute();
        if (!empty($kdpv))
        {
            $this->set_kdpv($id, $kdpv);
        }
    }
   /**
    *   Add new article
    * 
    */
    public function add($title,$url_title,$content,$content_short,$author_id, $kdpv,$kdpv_description)
    {
        $query = DB::insert($this->_tableArticles, array('title','url_title','content','content_short','author_id','kdpv_description'))
                    ->values(array(
                        $title,
                        $url_title,
                        $content,
                        $content_short,
                        $author_id,
                        $kdpv_description))
                    ->execute();
        if ($query and !empty($kdpv))
        {
            $this->set_kdpv($query[0], $kdpv);
        }
    }
    public function delete($id,$table = NULL)
    {
        if (empty($table))
        {
            $table = $this->_tableArticles;
        }
        $title = DB::select('title')
                ->from($table)
                ->where($table.'.id', '=', (int)$id)
                ->execute()
                ->get('title', 0);

        $query = DB::delete($table)
                ->where('id', '=', (int)$id)
                ->execute();
        return $title;
    }

    /** 
    *   Upload and processes article related pic(КДВП)
    *
    *   @param $post_id int     id of article. Also used for picture filename.  
    *   @param $image   file    image info
    **/
    public function set_kdpv($post_id, $image)
    {
        if (
                ! Upload::valid($image) OR
                ! Upload::not_empty($image) OR
                ! Upload::type($image, array('jpg', 'jpeg', 'png', 'gif')))
            {
                return FALSE;
            }
        $curdate = date('Y/m');
        $directory = DOCROOT.'public/images/articles/'.$curdate;
        $kdpvfullpath  = $directory.'/'.$post_id.'.jpg';
        $kdpvshortpath = 'public/images/articles/'.$curdate.'/'.$post_id.'.jpg';    
        
        Filesystem::make_path($directory);
        if ($file = Upload::save($image, NULL, $directory))
        {
            $picture = Image::factory($file);
            if (($picture->height > 400) OR ($picture->width > 680))
            {
                $picture->background('#FFF');
                $picture->resize(600, 400);
            }
            $picture->save($kdpvfullpath, 85);
            unlink($file);


            $query = DB::update($this->_tableArticles)
                        ->set(array('kdpv'=>$kdpvshortpath))
                        ->where('id', '=', (int)$post_id)
                        ->execute();
        }
    }
    /**
    *      delete article related image from folder and database
    *  
    *   @param $post_id int id of an article
    */
    public function delete_kdpv($post_id){

        $kdpv = DB::select('kdpv')
                ->from($this->_tableArticles)
                ->where('id', '=', (int)$post_id)
                ->execute()
                ->as_array();

        if (unlink($kdpv[0]['kdpv']) and (DB::update($this->_tableArticles)->set(array('kdpv'=>''))->where('id', '=', (int)$post_id)->execute()))
        {
            return TRUE;
        }
        else 
        { 
            return FALSE;
        }
    }

    /**
    *   Propose an article
    */
    public function propose_an_article($title,$content,$author_id){
        if (!empty($title) AND !empty($content))
        {
            $query = DB::insert($this->_tableProposedArticles, array('title','content','author_id'))
                ->values(array(
                    $title,
                    $content,
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
            throw new Exception('Название или текст статьи не могут быть пустыми');
        }
    }
}