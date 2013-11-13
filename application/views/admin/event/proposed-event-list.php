<h1>Список предложенных событий</h1>
<?php if(isset($message))
printf('<h3>%s</h3>',$message) ; ?>
    <table class="table-list">
            <tr>
            <th>
                Название
            </th>
            <th>
                Дата
            </th>
            <th>
                Автор
            </th>
            <th>
            </th>
            
        </tr>
    <?php foreach($events as $event): ?>

        <tr>
            <td>
                <a href="<?php echo URL::site('admin/event/proposed/'. $event['id']); ?>">
                    <?=HTML::chars($event['title']); ?>
                </a>
            </td>
            <td>
                <span class="nobr"><?= HelpingStuff::humanisedate($event['date']); ?></span>
            </td>
            <td>
                <?= HTML::chars($event['username']); ?>
            </td>
            <td>
                <a href="<?php echo URL::site('admin/event/proposed/?delete='. $event['id']); ?>">
                    Удалить
                </a>
            </td>
            
        </tr>
    <?php endforeach; ?>
    </table>
<?php  echo $pagination; ?>