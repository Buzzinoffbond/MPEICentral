<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Media extends Model
{
    protected $_tableAlbums = 'albums';
    protected $_tableImages = 'images';

    public function add_album($albumname,$description='')
    {
    	$dir = strtolower(Text::random('alnum', 20));
        $album_dir = DOCROOT.'public/images/albums/'.$dir;
        $thumb_dir = DOCROOT.'public/images/albums/'.$dir.'/thumbnails';
        $directories = array($album_dir, $thumb_dir);
        Filesystem::make_path($directories);
		
        $query = DB::insert('albums', array('title','dir','description'))
                    ->values(array(
						$albumname,
						$dir,
						$description))
                    ->execute();
        return $query;
    }
    /**
    *   Update album information
    */
    public function update_album($album_id,$album_name,$album_description){
        if(!empty($album_name))
        {
            $data['title'] = $album_name;
        }
        if(!empty($album_description))
        {
            $data['description'] = $album_description;
        }
            $query = DB::update($this->_tableAlbums)
                ->set($data)
                ->where('id', '=', $album_id)
                ->execute();
        return $query;
    }
    /**
    * Сортировка фотографий
    * @param array $sort_photos
    */
    public function sort_photos($sort_photos){
        if (!empty($sort_photos) AND is_array($sort_photos['sort'])) 
        {
            foreach ($sort_photos['sort'] as $index => $id)
            {
                $query = DB::update($this->_tableImages)
                ->set(array('sort'=>(int)$index))
                ->where('id','=',(int)$id)
                ->execute();
            }
        }
    }
    public function get_page_of_albums($offset,$limit)
    {
        $query = DB::select('*')
                ->from($this->_tableAlbums)
                ->order_by('id','DESC')
                ->limit((int)$limit)
                ->offset((int)$offset)
                ->execute();
         if($query)
            return $query;
         else
             return FALSE;
    }
    public function count_all_albums()
    {
        $query = DB::query(Database::SELECT, 'SELECT COUNT(*) AS "total_items" FROM '.$this->_tableAlbums)
            ->execute()
            ->get('total_items', 0);


        if($query)
            return intval($query);
        else
            return FALSE;
    }
    
    public function get_images_from_album($album_id)
    {
        $query = DB::select($this->_tableImages.'.*',array('albums.dir', 'album_dir'))
                ->from($this->_tableImages)
                ->join('albums')
                ->on('album_id','=','albums.id')
                ->where($this->_tableImages.'.album_id','=',(int)$album_id)
                ->order_by($this->_tableImages.'.sort','ASC')
                ->execute()
                ->as_array();
        if($query)
            return $query;
        else
            return array();
    }
    public function get_album_info($album_id)
    {
        $query = DB::select($this->_tableAlbums.'.*')
                ->from($this->_tableAlbums)
                ->where('id','=',(int)$album_id)
                ->limit(1)
                ->execute()
                ->as_array();
        if($query)
            return $query[0];
        else
            return array();
    }
    public function add_image($image_file,$album_id)
    {
        $albums=DB::select('dir','cover')
            ->from($this->_tableAlbums)
            ->where('id','=',((int)$album_id))
            ->execute()
            ->current();
        $directory = DOCROOT.'public/images/albums/'.$albums['dir'].'/';
        $directorythumb = DOCROOT.'public/images/albums/'.$albums['dir'].'/thumbnails/';
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
                    if ($file = Upload::save($image_file, NULL, $directory))
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
                        $image->save($directory.$filename); //сохраняем оригинал
                        // Обрезаем по высчитанным размерам до нужной пропорции
                        $image->crop($crop_width, $crop_height);
                        // Масштабируем картинку то точных размеров
                        $image->resize($size_width, $size_height, Image::NONE);
                        // Сохраняем изображение в файл
                        $image->save($directorythumb.$filename);
                        
                        unlink($file);
                    }
                }
            $query = DB::insert($this->_tableImages, array('filename','album_id'))
            ->values(array($filename,$album_id))
            ->execute();
    
            //обложка альбома
            if(empty($albums['cover']))
            {
                DB::update($this->_tableAlbums)
                        ->set(array('cover'=>$filename))
                        ->where('id', '=',(int)$album_id)
                        ->execute();
            }
            return $filename;
    }
    public function delete_album($album_id){
        $album=DB::select('dir')
            ->from($this->_tableAlbums)
            ->where('id','=',((int)$album_id))
            ->execute()
            ->current();

        DB::delete($this->_tableImages)
                    ->where('album_id', '=', (int)$album_id)
                    ->execute();
        $deleted_album = DB::delete('albums')
                    ->where('id', '=', (int)$album_id)
                    ->execute();
        $albumDir=DOCROOT.'public/images/albums/'.$album["dir"];
        Filesystem::delete_files($albumDir, NULL, TRUE,TRUE, 1);
        return $deleted_album;
    }

    public function delete_photo($photo_id)
    {
        $deleted_album = DB::delete($this->_tableImages)
                    ->where('id', '=', (int)$photo_id)
                    ->execute();
        if ($deleted_album)
        {
            return TRUE;
        }
        return FALSE;
    }
    public function set_album_cover($album_id, $photo_id)
    {
        $album = DB::select('filename')
            ->from($this->_tableImages)
            ->where('id','=',((int)$photo_id))
            ->execute()
            ->as_array();

        if(!empty($album))
            {
                $result = DB::update($this->_tableAlbums)
                    ->set(array('cover'=>$album[0]['filename']))
                    ->where('id', '=',(int)$album_id)
                    ->execute();

                    return $result;
            }
        return FALSE;

        
    }
}//end Media
