<div class="layout-content">
<h1 class="title-more-space">Новости</h1>
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
<?php  echo $pagination; ?>
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
if ( $(window).width() < 600) {
  $("<?php echo implode(',',$oneColumn);?>").appendTo(".first-column");
}
if ( ($(window).width() > 600) && ($(window).width() < 1024)) {
  $("<?php echo implode(',',$firstTwoColumns);?>").appendTo(".first-column");
  $("<?php echo implode(',',$secondTwoColumns);?>").appendTo(".second-column");
}
else {
  $("<?php echo implode(',',$firstThreeColumns);?>").appendTo(".first-column");
  $("<?php echo implode(',',$secondThreeColumns);?>").appendTo(".second-column");
  $("<?php echo implode(',',$thirdThreeColumns);?>").appendTo(".third-column");
}
$(window).resize(function() {
if ( $(window).width() < 600) 
  {
    var oneColumn = $(".articles").find("<?php echo implode(',',$oneColumn);?>").toArray().sort(sorter);
    $.each(oneColumn, function (index, value) {
    $(".first-column").append(value); });
  }
if ( ($(window).width() > 600) && ($(window).width() < 1024)) 
  {
    var firstTwoColumns = $(".articles").find("<?php echo implode(',',$firstTwoColumns);?>").toArray().sort(sorter);
    $.each(firstTwoColumns, function (index, value) {
    $(".first-column").append(value); });

    var secondTwoColumns = $(".articles").find("<?php echo implode(',',$secondTwoColumns);?>").toArray().sort(sorter);
    $.each(secondTwoColumns, function (index, value) {
    $(".second-column").append(value); });
  }
if ( $(window).width() > 1024) 
  {
    var firstThreeColumns = $(".articles").find("<?php echo implode(',',$firstThreeColumns);?>").toArray().sort(sorter);
    $.each(firstThreeColumns, function (index, value) {
    $(".first-column").append(value); });

    var secondThreeColumns = $(".articles").find("<?php echo implode(',',$secondThreeColumns);?>").toArray().sort(sorter);
    $.each(secondThreeColumns, function (index, value) {
    $(".second-column").append(value); });

    var secondThreeColumns = $(".articles").find("<?php echo implode(',',$thirdThreeColumns);?>").toArray().sort(sorter);
    $.each(secondThreeColumns, function (index, value) {
    $(".third-column").append(value); });
  }
});


});
</script>