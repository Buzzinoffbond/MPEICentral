<div class="upper-content">
  <div class="center-matrix">
  <?php foreach($matrixitems as $matrixitem): ?>
  <a href="<?=URL::site('event/'.$matrixitem['id'].'/'.$matrixitem['url_title']) ?>">
  <div class="matrix-item" style="background: url(<?= $matrixitem['poster'];?>); background-size: cover; background-position: center;">
  	<div class="matrix-item-text">
  		<span class="matrix-item-title"><?= $matrixitem['title'];?></span>
  		<div class="sepaline"></div>
  		<span class="matrix-item-date"><?= HelpingStuff::humanisedate($matrixitem['date']);?></span>
  	</div>
  </div>
  </a>
  <?php endforeach; ?>
  </div>
</div>
<div class="layout">
<div class="layout-content">
<h1>Новости</h1>
<div class="articles">
  <div class="first-column">
    <?php $i=0; foreach($articles as $article): $i++; $items[]='item'.$i;?>
    <div class="post" data-sort="<?= $i;?>" id="item<?= $i;?>">
    <div class="article-date"><?= HelpingStuff::humanisedate($article["date"]);?></div>
    <h3 class="post-small-title"><a href="<?=URL::site("articles/".$article["id"]."/".$article["url_title"]);?>"><?=$article["title"];?></a></h3>
    <?php if (!empty($article['kdpv']))
    {
        printf('<a href="%s"><img src="%s" class="kdpv-small"><div class="clear"></div></a>',URL::site("articles/".$article["id"]."/".$article["url_title"]), URL::site($article['kdpv']));
    }
    ?>
    <p><?= strip_tags($article["content_short"]);?></p>
    <span class="article-author">
    <a href="<?= URL::site("user/".$article["username"]);?>"><?= $article["username"];?></a>
    </span>
    <div class="clear"></div>
  </div>
  <?php endforeach; ?></div>
  <div class="second-column"></div>
  <div class="third-column"></div>
  <div class="clear"></div>
</div>
<script>
<?php
$count=count($items);
#Одна колонка
for ($i=0; $i<$count; $i++)
  { 
    $oneColumn[]='#'.$items[$i];
  }
#Две колонки
for ($i=0; $i<$count; $i=$i+2)
  { 
    $firstTwoColumns[]='#'.$items[$i];
  }
for ($i=1; $i<$count; $i=$i+2)
  { 
    $secondTwoColumns[]='#'.$items[$i];
  }
#Три колонки
for ($i=0; $i<$count; $i=$i+3)
  {  
    $firstThreeColumns[]='#'.$items[$i];
  }
for ($i=1; $i<$count; $i=$i+3)
  { 
    $secondThreeColumns[]='#'.$items[$i];
  }
for ($i=2; $i<$count; $i=$i+3)
  { 
    $thirdThreeColumns[]='#'.$items[$i];
  }
?>
function sorter(a, b) {
    return a.getAttribute('data-sort') - b.getAttribute('data-sort');
};
$(document).ready(function(){
if ($(".mediaquery-status").css('width') === '0px')  {
  $("<?php echo implode(',',$oneColumn);?>").appendTo(".first-column");
}
if ($(".mediaquery-status").css('width') === '600px')  {
  $("<?php echo implode(',',$firstTwoColumns);?>").appendTo(".first-column");
  $("<?php echo implode(',',$secondTwoColumns);?>").appendTo(".second-column");
}
else {
  $("<?php echo implode(',',$firstThreeColumns);?>").appendTo(".first-column");
  $("<?php echo implode(',',$secondThreeColumns);?>").appendTo(".second-column");
  $("<?php echo implode(',',$thirdThreeColumns);?>").appendTo(".third-column");
}
$(window).resize(function() {
if ($(".mediaquery-status").css('width') === '0px') 
  {
    var oneColumn = $(".articles").find("<?php echo implode(',',$oneColumn);?>").toArray().sort(sorter);
    $.each(oneColumn, function (index, value) {
    $(".first-column").append(value); });
  }
if ($(".mediaquery-status").css('width') === '600px')
  {
    var firstTwoColumns = $(".articles").find("<?php echo implode(',',$firstTwoColumns);?>").toArray().sort(sorter);
    $.each(firstTwoColumns, function (index, value) {
    $(".first-column").append(value); });

    var secondTwoColumns = $(".articles").find("<?php echo implode(',',$secondTwoColumns);?>").toArray().sort(sorter);
    $.each(secondTwoColumns, function (index, value) {
    $(".second-column").append(value); });
  }
if ($(".mediaquery-status").css('width') === '1024px')
  {
    var firstThreeColumns = $(".articles").find("<?php echo implode(',',$firstThreeColumns);?>").toArray().sort(sorter);
    $.each(firstThreeColumns, function (index, value) {
    $(".first-column").append(value); });

    var secondThreeColumns = $(".articles").find("<?php echo implode(',',$secondThreeColumns);?>").toArray().sort(sorter);
    $.each(secondThreeColumns, function (index, value) {
    $(".second-column").append(value); });

    var thirdThreeColumns = $(".articles").find("<?php echo implode(',',$thirdThreeColumns);?>").toArray().sort(sorter);
    $.each(thirdThreeColumns, function (index, value) {
    $(".third-column").append(value); });
  }
});


});
</script>
</div>
<div class="grey-box">
  <div class="layout-content all-articles-link">
<a href="<?=URL::site('articles');?>">Ко всем новостям&rarr;</a>
  </div>
</div>
<div class="layout-content">
  <h1>События</h1>
    <?php $cur_month_year=''; foreach($events as $event):
    $month_year=HelpingStuff::humanisedate($event['date'],'monthyear');
    if ($month_year!=$cur_month_year) {
      $cur_month_year=$month_year;
      printf('<div class="events-dateseparator"><h2>%s</h2></div>',$cur_month_year);
     } ?>
    <div class="event">
        <a class='events-link' href="<?=URL::site('event/'.$event['id']);?>">
          <?php if(!empty($event['poster'])){
            printf('<img class="events-poster" src="%s">',URL::site($event['poster']));
          } ?>
          <h3 class="events-title"><?= HTML::chars($event['title']);?></h3>
        </a>
        <div class="events-description">
          <span class="events-date"><?=HelpingStuff::humanisedate($event['date']);?></span>
        </div>

      <div class="clear"></div>
    </div>
      <?php endforeach; ?>
  <div class="clear"></div>
</div>
<div class="grey-box">
  <div class="layout-content all-articles-link">
<a href="<?=URL::site('events');?>">Ко всем событиям&rarr;</a>
  </div>
</div>
<div class="layout-content">
&nbsp;
</div>
</div>

