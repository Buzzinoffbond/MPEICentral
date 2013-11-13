<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Contest extends Model
{
    protected $_tableContestInfo = 'contest_info';
    protected $_tableCompetitors = 'contest_competitors';
    protected $_tableCompetitorsImages = 'contest_competitors_images';
    protected $_tableContestVotes = 'contest_votes';

    public function get_contests()
    {
        $query = DB::select('id','title', 'url_title', 'cover')
        ->from($this->_tableContestInfo)
        ->where('active','=',TRUE)
        ->execute()
        ->as_array();
        
        if (!$query){return FALSE;}
        return $query;
    }
    public function get_page($start,$nums)
    {
        $query = DB::select('id','title', 'url_title','active')
        ->from($this->_tableContestInfo)
        ->order_by('id','DESC')
        ->limit((int)$nums)
        ->offset((int)$start)
        ->execute()
        ->as_array();
        
        if (!$query){return FALSE;}
        return $query;
    }
    /**
    *	info about contest
    *	@param $contest_id	int	id of contest 
    **/
    public function get_info($contest_id)
    {
    	$query = DB::select('*')
    	->from($this->_tableContestInfo)
    	->where('id','=',(int)$contest_id)
    	->execute()
    	->current();

    	if (!$query){return FALSE;}
    	
    	return $query;
    }
    public function count_all()
    {
        $query = DB::query(Database::SELECT, 'SELECT COUNT(*) AS "total_items" FROM '.$this->_tableContestInfo)
            ->execute()
            ->get('total_items', 0);


        if($query)
            return intval($query);
        else
            return FALSE;
    }
    /**
    *	get competitors of particular contest
    *	@param $contest_id	int	id of contest 
    **/
    public function get_competitors($contest_id)
    {
    	$query = DB::select('*')
    	->from($this->_tableCompetitors)
    	->where('contest_id','=',(int)$contest_id)
    	->execute()
    	->as_array();

    	if (!$query){return FALSE;}
    	
    	return $query;
    }
    /**
    *   get info about competitor
    *   @param $competitor_id  int id of contest 
    **/
    public function get_competitor($competitor_id)
    {
        $query = DB::select('*')
        ->from($this->_tableCompetitors)
        ->where('id','=',(int)$competitor_id)
        ->execute()
        ->current();

        if (!$query){return FALSE;}
        
        return $query;
    }
    /**
    *	creates new contest
    *	@param $title 		string
    *	@param $url_title 	string
    **/
    public function add($title, $url_title='')
    {
        $query = DB::insert($this->_tableContestInfo, array('title','url_title'))
                    ->values(array(
                        $title,
                        $url_title))
                    ->execute();
        $dir     = DOCROOT.'public/images/contests/'.(int)$query[0];
        Filesystem::make_path($dir);
        if ($query)
        {
        	return $query;
        }
        return FALSE;
    }
    /**
    *	update contest
    *	@param $contest_id	int		id of contest
    *	@param $title 		string
    *	@param $data		array
    **/
    public function update($contest_id,$title, $url_title, $description, $cover)
    {
        $filename='';
        if(!empty($cover))
        {
            if (
                Upload::valid($cover) OR
                Upload::not_empty($cover) OR
                Upload::type($cover, array('jpg', 'jpeg', 'png', 'gif')) OR
                Upload::size($cover,'3M'))
            {
                $old_cover = DB::select('cover')->from($this->_tableContestInfo)->where('id','=',(int)$contest_id)->execute()->current();
                if(!empty($old_cover))
                {
                    $tmp_dir = DOCROOT.'public/images/tmp_upload/';
                    $dir     = DOCROOT.'public/images/contests/'.(int)$contest_id.'/';
                    $filename = strtolower(Text::random('alnum', 20)).'.jpg';
                    $file = Upload::save($cover, NULL, $tmp_dir);
                    $image = Image::factory($file);
                    $image->save($dir.$filename);
                    unlink($file);
                    unlink($dir.$old_cover['cover']);
                }
            }
            
        }

        $url_title = URL::title(HelpingStuff::rusToLat($url_title), '_');
        $query = DB::update($this->_tableContestInfo)
                    ->set(array('title'=>$title,'url_title'=>$url_title,'description'=>$description,'cover'=>$filename))
                    ->where('id', '=', (int)$contest_id)
                    ->execute();
        if ($query)
        {
        	return $query;
        }
        return FALSE;
    }
    /**
    *   creates new competitor
    *   @param $contest_id  int     id of contest
    *   @param $name        string
    **/
    public function addcompetitor($contest_id,$name)
    {
        $url_title = URL::title(HelpingStuff::rusToLat($name), '_');
        $query = DB::insert($this->_tableCompetitors, array('contest_id','name','url_title'))
                    ->values(array(
                        (int)$contest_id,
                        $name,
                        $url_title))
                    ->execute();

        $dir        = DOCROOT.'public/images/contests/'.(int)$contest_id.'/'.(int)$query[0];
        $thumb_dir  = DOCROOT.'public/images/contests/'.(int)$contest_id.'/'.(int)$query[0].'/thumbnails/';
        Filesystem::make_path(array($dir, $thumb_dir));

        if ($query)
        {
            return $query;
        }
        return FALSE;
    }
    /**
    * sort competitors photos
    * @param array $sort_photos
    */
    public function sort_competitor_photos($id,$sort_photos)
    {
        parse_str($sort_photos, $sort_photos_arr);
        if (!empty($sort_photos_arr) AND is_array($sort_photos_arr['sort'])) 
        {
            foreach ($sort_photos_arr['sort'] as $index => $id)
            {
                $query = DB::update($this->_tableCompetitorsImages)
                ->set(array('sort'=>(int)$index))
                ->where('id','=',(int)$id)
                ->execute();
            }
        }
    }
    /**
    * get competitors photos
    * @param int $competitor_id
    */
    public function get_competitor_photos($competitor_id)
    {
        $query = DB::select('*')
                ->from($this->_tableCompetitorsImages)
                ->where('competitor_id','=',(int)$competitor_id)
                ->order_by('sort','ASC')
                ->execute()
                ->as_array();
        if($query)
            return $query;
        else
            return array();
    }
    /**
    *   Set competitor photo
    *   @param $competitor_id   int
    *   @param $image_id        int
    **/
    public function default_comp_image($competitor_id,$image_id)
    {
        $image = DB::select('filename')->from($this->_tableCompetitorsImages)->where('id','=',(int)$image_id)->execute()->current();
        $query = DB::update($this->_tableCompetitors)->set(array('filename'=>$image['filename']))->where('id','=',(int)$competitor_id)->execute();
        if ($query)
        {
            return TRUE;
        }
        return FALSE;
    }
    /**
    *   Adds new competitor photo
    *   @param $competitor_id   int     id of competitor
    *   @param $image_file      array   image
    **/
    public function add_competitor_image($competitor_id,$image_file)
    {
        $filename = $this->save_image($competitor_id,$image_file);

        $this->save_image_to_DB($competitor_id, $filename);

        $check = DB::select('filename')->from($this->_tableCompetitors)->where('id','=',$competitor_id)->execute()->current();
        if (empty($check['filename']))
        {
            DB::update($this->_tableCompetitors)->set(array('filename'=>$filename))->where('id','=',$competitor_id)->execute();
        }
    }
    /**
    *   saves competitor photo
    *   @param $competitor_id   int     id of competitor
    *   @param $image_file      array   image
    **/
    public function save_image($competitor_id,$image_file)
    { 
        $competitor = DB::select('id','contest_id')
            ->from($this->_tableCompetitors)
            ->where('id','=',(int)$competitor_id)
            ->execute()
            ->current();

        $dir        = DOCROOT.'public/images/contests/'.(int)$competitor['contest_id'].'/'.(int)$competitor['id'].'/';
        $thumb_dir  = DOCROOT.'public/images/contests/'.(int)$competitor['contest_id'].'/'.(int)$competitor['id'].'/thumbnails/';
        $tmp_dir    = DOCROOT.'public/images/tmp_upload';
                if (
                    !Upload::valid($image_file) OR
                    !Upload::not_empty($image_file) OR
                    !Upload::type($image_file, array('jpg', 'jpeg', 'png', 'gif')) OR
                    !Upload::size($image_file,'3M'))
                {
                    return FALSE;
                }
                else
                {
                    if ($file = Upload::save($image_file, NULL, $tmp_dir))
                    {   
                        $filename = strtolower(Text::random('alnum', 20)).'.jpg';
                        // Размеры, до которых будем обрезать
                        $size_width = 200;
                        $size_height = 200;
                        // Открываем изображение
                        $image = Image::factory($file);
                        // Подсчитываем соотношение сторон картинки
                        $ratio = $image->width / $image->height;
                        // Соотношение сторон нужных размеров
                        $original_ratio = $size_width / $size_height;
                        // Размеры, до которых обрежем картинку до масштабирования
                        $crop_width = $image->width;
                        $crop_height = $image->height;
                        // Смотрим соотношения
                        if($ratio > $original_ratio)
                        {
                            // Если ширина картинки слишком большая для пропорции,
                            // то будем обрезать по ширине
                            $crop_width = round($original_ratio * $crop_height);
                        }
                        else
                        {
                            // Либо наоборот, если высота картинки слишком большая для пропорции,
                            // то обрезать будем по высоте
                            $crop_height = round($crop_width / $original_ratio);
                        }
                        $image->save($dir.$filename); //сохраняем оригинал
                        // Обрезаем по высчитанным размерам до нужной пропорции
                        $image->crop($crop_width, $crop_height);
                        // Масштабируем картинку то точных размеров
                        $image->resize($size_width, $size_height, Image::NONE);
                        // Сохраняем изображение в файл
                        $image->save($thumb_dir.$filename);
                        
                        unlink($file);
                        
                    }
                    else
                    {
                        return FALSE;
                    }
                }
            return $filename;
    }
    public function save_image_to_DB($competitor_id,$filename)
    {
        $query = DB::insert($this->_tableCompetitorsImages, array('filename','competitor_id'))
                ->values(array($filename,(int)$competitor_id))
                ->execute();
    }

    /**
    *   delete competitor photo
    *   @param $photo_id    int
    **/
    public function delete_photo($photo_id)
    {
        $image = DB::select($this->_tableCompetitorsImages.'.filename','contest_id','competitor_id',$this->_tableCompetitorsImages.'.id')
            ->from($this->_tableCompetitorsImages)
            ->where($this->_tableCompetitorsImages.'.id','=',(int)$photo_id)
            ->join($this->_tableCompetitors)
            ->on($this->_tableCompetitors.'.id','=',$this->_tableCompetitorsImages.'.competitor_id')
            ->execute()
            ->current();

        $query = DB::delete($this->_tableCompetitorsImages)
                    ->where('id', '=', (int)$image['id'])
                    ->execute();
        $image_file        = DOCROOT.'public/images/contests/'.(int)$image['contest_id'].'/'.(int)$image['competitor_id'].'/'.$image['filename'];
        $image_file_thumb  = DOCROOT.'public/images/contests/'.(int)$image['contest_id'].'/'.(int)$image['competitor_id'].'/thumbnails/'.$image['filename'];
        unlink($image_file);
        unlink($image_file_thumb);
        if ($query)
        {
            return TRUE;
        }
        return FALSE;
    }    
    /**
    *	create or update competitor
    *	@param $competitor_id	int		id of contest
    *	@param $name 			string
    *	@param $url_title 		string
    *	@param $description 	string
    **/
    public function competitor_data($competitor_id,$name, $url_title, $description)
    {
        $query = DB::update($this->_tableCompetitors)
            ->set(array(
                  	'name'=>$name,
                   	'url_title'=>$url_title,
                   	'description'=>$description))
            ->where('id', '=', (int)$competitor_id)
            ->execute();

        if ($query)
        {
        	return $query;
        }
        return FALSE;
    }
    public function delete_contest($contest_id)
    {
        
        if(!empty($contest_id))
        {
            //delete contest from db
            $query = DB::delete($this->_tableContestInfo)
                ->where('id', '=', (int)$contest_id)
                ->execute();
            //delete competitors from db
            $delCompetitors = DB::delete($this->_tableCompetitors)
                ->where('contest_id', '=', (int)$contest_id)
                ->execute();
            //delete competitor images from db
            $competitors = DB::select('id')->from($this->_tableCompetitors);
            $delCompPhotos = DB::delete($this->_tableCompetitorsImages)
                ->where('competitor_id', 'NOT IN', $competitors)
                ->execute();
    
            $contestDir=DOCROOT.'public/images/contests/'.(int)$contest_id;
            Filesystem::delete_files($contestDir, NULL, TRUE,TRUE, 1);
            return TRUE;
        }
        return FALSE;
    }
    public function toggle_active($contest_id)
    {
        $check = DB::select('active')->from($this->_tableContestInfo)->where('id','=', (int)$contest_id)->execute()->current();
        $query = FALSE;
        if($check['active']==0)
        {
            $query = DB::update($this->_tableContestInfo)
            ->set(array('active'=>1))->execute();
        }
        else
        {
            $query = DB::update($this->_tableContestInfo)
            ->set(array('active'=>0))->execute();
        }
        return $query;
    } 
    /**
    *    vote
    *
    *
    **/
    public function vote($user_id, $competitor_id, $contest_id)
    {
        $settings = DB::select('type')->from($this->_tableContestInfo)->where('id','=',$contest_id)->execute()->current();
        $votehistory = DB::select('competitor_id')
        ->from($this->_tableContestVotes)
        ->where('user_id','=',(int)$user_id)
        ->and_where('contest_id','=',(int)$contest_id)
        ->execute()
        ->as_array();


        switch ($settings['type']) {
            case '1':
                //singlevote
                if (count($votehistory)==0)
                {
                    $vote = DB::insert($this->_tableContestVotes, array('user_id','competitor_id','contest_id'))
                    ->values(array(
                        (int)$user_id,
                        (int)$competitor_id,
                        (int)$contest_id))
                    ->execute();
                    return TRUE;
                }
                break;
            
            case '2':
                //multivote
                $disable = FALSE;
                foreach ($votehistory as $value)
                {
                    if ($value['competitor_id']==(int)$competitor_id)
                    {
                        $disable = TRUE;
                    }
                }
                if (!$disable)
                {
                    $vote = DB::insert($this->_tableContestVotes, array('user_id','competitor_id','contest_id'))
                    ->values(array(
                        (int)$user_id,
                        (int)$competitor_id,
                        (int)$contest_id))
                    ->execute();

                    return TRUE;
                }
                break;
        }
        return FALSE;
    }
    public function get_voteInfo($contest_id,$user_id)
    {
        $settings = DB::select('type')->from($this->_tableContestInfo)->where('id','=',$contest_id)->execute()->current();
        $votehistory = DB::select('competitor_id')
        ->from($this->_tableContestVotes)
        ->where('user_id','=',(int)$user_id)
        ->and_where('contest_id','=',(int)$contest_id)
        ->execute();
        switch ($settings['type']) {
            case '1':
                //singlevote
                if (count($votehistory)==0)
                {
                    return array(TRUE,array());
                }
                break;
            
            case '2':
                    //multivote
                    $voteids = Arr::rotate($votehistory);
                    return array(TRUE,$voteids['competitor_id']);
                break;
        }
        $voteids = Arr::rotate($votehistory);
        return array(FALSE,$voteids['competitor_id']);
    }
}