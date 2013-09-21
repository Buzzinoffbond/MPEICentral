<?php defined('SYSPATH') or die('No direct script access.');
 
class HelpingStuff
{
 	
 	static function humanisedate($dateToHuminise,$format="fulldate"){ //fulldate -дата с днем недели. date- только дата. datetime- дата и время. monthyear- месяц и год age - возраст
 		$humaniseddate='';
    if(!empty($dateToHuminise))
			{
          $dateToHuminise = date("Y-m-d H:i:s", strtotime($dateToHuminise));
				  preg_match('/(\d{4})-(\d{2})-(\d{2})(\s(\d{2}):(\d{2}))?/',$dateToHuminise,$date);
				  $day_of_week=date("N", mktime(0, 0, 0, $date['2'], $date['3'], $date['1']));
				  $day=date("j", mktime(0, 0, 0, $date['2'], $date['3'], $date['1']));
          //месяц
          if($date['2']=='1'){$month='Январь';}
          if($date['2']=='2'){$month='Февраль';}
          if($date['2']=='3'){$month='Март';}
          if($date['2']=='4'){$month='Апрель';}
          if($date['2']=='5'){$month='Май';}
          if($date['2']=='6'){$month='Июнь';}
          if($date['2']=='7'){$month='Июль';}
          if($date['2']=='8'){$month='Август';}
          if($date['2']=='9'){$month='Сентябрь';}
          if($date['2']=='10'){$month='Октябрь';}
          if($date['2']=='11'){$month='Ноябрь';}
          if($date['2']=='12'){$month='Декабрь';}
          //месяц в родительном падеже
				  if($date['2']=='1'){$monthRod='Января';}
				  if($date['2']=='2'){$monthRod='Февраля';}
				  if($date['2']=='3'){$monthRod='Марта';}
				  if($date['2']=='4'){$monthRod='Апреля';}
				  if($date['2']=='5'){$monthRod='Мая';}
				  if($date['2']=='6'){$monthRod='Июня';}
				  if($date['2']=='7'){$monthRod='Июля';}
				  if($date['2']=='8'){$monthRod='Августа';}
				  if($date['2']=='9'){$monthRod='Сентября';}
				  if($date['2']=='10'){$monthRod='Октября';}
				  if($date['2']=='11'){$monthRod='Ноября';}
				  if($date['2']=='12'){$monthRod='Декабря';}
          //день недели
				  if($day_of_week=='7'){$day_of_week='Воскресение';}
				  if($day_of_week=='1'){$day_of_week='Понедельник';}
				  if($day_of_week=='2'){$day_of_week='Вторник';}
				  if($day_of_week=='3'){$day_of_week='Среда';}
				  if($day_of_week=='4'){$day_of_week='Четверг';}
				  if($day_of_week=='5'){$day_of_week='Пятница';}
				  if($day_of_week=='6'){$day_of_week='Суббота';}
          if($format=="fulldate")
          {
            $humaniseddate= $day_of_week.", <nobr>".$day." ".$monthRod.",</nobr> ".$date['1'];
          }
          if($format=="datetime")
          {
            $humaniseddate= $day." ".$monthRod.", ".$date['1']." в ".$date['4'].":".$date['5'];
          }
          if($format=="date")
          {
            $humaniseddate= $day." ".$monthRod.", ".$date['1'];
          }
          if($format=="monthyear")
          {
            $humaniseddate= $month.", ".$date['1'];
          }
          if ($format == "age")
          {
            $bday = new DateTime($dateToHuminise);
            $today = new DateTime(date("Y-m-d H:i:s"));
            $diff = $today->diff($bday);
            $age = $diff->y;
            $lastnumber = substr($age, -1, 1);
            if (($lastnumber >1) AND ($lastnumber < 5))
            {
              $years = 'года';
            }
            elseif ($lastnumber == 1) {
              $years = 'год';
            }
            else{
              $years = 'лет';
            }
            $humaniseddate = $age.' '.$years;
          }
			}
		return $humaniseddate;
 	}

  static function embedmedia($serializedmedia){
  	$media=unserialize($serializedmedia);
  	if($media){
    foreach ($media as $item)
    {
  		$url_parts=parse_url($item);
      $image=pathinfo($url_parts['path']);
      if(isset($url_parts['host']) or isset($image['extension']))
      {
        if(isset($url_parts['host']))
        {
  		    if (preg_match('/(youtube.com|youtu.be)/', $url_parts['host']))
  		    {	//youtube
            $regexstr = '#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#';
            preg_match($regexstr,$item,$youtubeid);
            $youtubeid=$youtubeid['0'];
            $item='<iframe src="http://www.youtube.com/embed/'.$youtubeid.'" frameborder="0" allowfullscreen></iframe>';
          }
          if (preg_match('/vimeo\.com/', $url_parts['host']))
          {	//vimeo
            $regexstr = '/(videos|video|videos|channels\/[a-z0-9]+|\.com)\/([\d]+)/';
            preg_match($regexstr,$item,$vimeoid);
            $vimeoid=$vimeoid['2'];
            $item='<iframe src="http://player.vimeo.com/video/'.$vimeoid.'" frameborder="0" allowFullScreen></iframe>';
          }
        }
        if(isset($image['extension']))
        {
          if (preg_match('/(png|jpeg|jpg|gif)/',$image['extension'])) 
          {
            $item='<img src="'.$item.'">';
          }
        }
      }
      else
      {
        $item='<div class="showcase-text-box"><div class="showcase-content-wrapper">'.$item.'</div></div>';
      }
      $embedmedia[]=$item;	
  	}
    return $embedmedia;
    }
    else{
      return '';
    }	
  }
  static function decodeInstitute($inst='0',$length='1')//length 0-цифры 1-аббревиатура 2-полная запись 
  {
    $inst=intval($inst);
    $length=intval($length);
      if ($length==0)
    {
      if      ($inst==1)  {$institute=1;}
      elseif  ($inst==2)  {$institute=2;}
      elseif  ($inst==3)  {$institute=3;}
      elseif  ($inst==4)  {$institute=4;}
      elseif  ($inst==5)  {$institute=5;}
      elseif  ($inst==6)  {$institute=6;}
      elseif  ($inst==7)  {$institute=7;}
      elseif  ($inst==8)  {$institute=8;}
      elseif  ($inst==9)  {$institute=9;}
      elseif  ($inst==10) {$institute=10;}
      elseif  ($inst==11) {$institute=11;}
      elseif  ($inst==12) {$institute=12;}
      elseif  ($inst==13) {$institute=13;}
      else {$institute=0;}
    }
    if ($length==1)
    {
      if      ($inst==1)  {$institute='АВТИ';}
      elseif  ($inst==2)  {$institute='ИТАЭ';}
      elseif  ($inst==3)  {$institute='ИПЭЭФ';}
      elseif  ($inst==4)  {$institute='ИРЭ';}
      elseif  ($inst==5)  {$institute='ИЭТ';}
      elseif  ($inst==6)  {$institute='ИЭЭ';}
      elseif  ($inst==7)  {$institute='ЭнМИ';}
      elseif  ($inst==8)  {$institute='ГПИ';}
      elseif  ($inst==9)  {$institute='ИМЭЭПБ';}
      elseif  ($inst==10) {$institute='ЦП ИИЭБ';}
      elseif  ($inst==11) {$institute='ФДП';}
      elseif  ($inst==12) {$institute='ЦП ИЛ';}
      elseif  ($inst==13) {$institute='ЦП МЭИ-ФЕСТО';}
      else {$institute='';}
    }
    if ($length==2)
    {
      if      ($inst==1)  {$institute='Институт автоматики и вычислительной техники';}
      elseif  ($inst==2)  {$institute='Институт тепловой и атомной энергетики';}
      elseif  ($inst==3)  {$institute='Институт проблем энергетической эффективности';}
      elseif  ($inst==4)  {$institute='Институт радиотехники и электроники';}
      elseif  ($inst==5)  {$institute='Институт электротехники';}
      elseif  ($inst==6)  {$institute='Институт электроэнергетики';}
      elseif  ($inst==7)  {$institute='Институт энергомашиностроения и механики';}
      elseif  ($inst==8)  {$institute='Гуманитарно-прикладной институт';}
      elseif  ($inst==9)  {$institute='Институт менеджмента и экономики в энергетике и промышленности';}
      elseif  ($inst==10) {$institute='Центр подготовки «Институт информационной и экономической безопасности»';}
      elseif  ($inst==11) {$institute='Факультет довузовской подготовки';}
      elseif  ($inst==12) {$institute='Центр подготовки «Институт лингвистики»';}
      elseif  ($inst==13) {$institute='Центр подготовки «Российско-германский институт бизнеса и промышленной автоматики МЭИ-ФЕСТО»';}
      else {$institute='';}
    }

    return $institute;
  }
    public static function rusToLat($str)
    {
        $tr = array(
            "А"=>"a","Б"=>"b","В"=>"v","Г"=>"g",
            "Д"=>"d","Е"=>"e","Ж"=>"j","З"=>"z","И"=>"i",
            "Й"=>"y","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n",
            "О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t",
            "У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"ts","Ч"=>"ch",
            "Ш"=>"sh","Щ"=>"sch","Ъ"=>"","Ы"=>"yi","Ь"=>"",
            "Э"=>"e","Ю"=>"yu","Я"=>"ya","а"=>"a","б"=>"b",
            "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
            "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
            "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
            "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
            "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
            "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"," "=>"_"
        );

        return strtr($str,$tr);
    }
}