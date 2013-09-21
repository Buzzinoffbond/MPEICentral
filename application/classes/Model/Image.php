<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Image extends Model
{
	public function avatar_processing($file, $id)
	{
		$directory = DOCROOT.'public/images/userpics/';
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
        // Обрезаем по высчитанным размерам до нужной пропорции
        $image->crop($crop_width, $crop_height);
        // Масштабируем картинку то точных размеров
        $image->resize($size_width, $size_height, Image::NONE);
        // Сохраняем изображение в файл
        $image->save($directory.$filename);
        // Delete the temporary file
        unlink($file);

        Model::factory('Users')->update_userpic($filename,$id);

        return $filename;
	}

	public function upload_avatar($image, $user_id)
	{
        if (
            ! Upload::valid($image) OR
            ! Upload::not_empty($image) OR
            ! Upload::type($image, array('jpg', 'jpeg', 'png', 'gif')))
        {
            return FALSE;
        }

        $tmp_filename = strtolower(Text::random('alnum', 20)).'.jpg';
        $directory = DOCROOT.'public/images/tmp_upload/';

        $avatar = $directory.$tmp_filename;
        if ($file = Upload::save($image, $tmp_filename, $directory))
        {
        	$filename = Model::factory('Image')->avatar_processing($avatar,$user_id);
        	return $filename;
        }

        return FALSE;

	}


}