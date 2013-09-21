<div class="layout-content">
  <h1 class="left">События</h1>
  <a class="secondary-nav-link" href="<?= URL::site('events/calendar')?>">Календарь</a>
  <div class="clear"></div>
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
      <?= $pagination;?>
</div>