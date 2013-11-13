<h1>Список всех событий</h1>
<h3><?php if(isset($message))
echo $message; ?></h3>
    <table class="table-list">
        <tr>
            <th>
                Название
            </th>
            <th>
                Дата проведения
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
                <a href="<?php echo URL::site('admin/event/edit/'. $event['id']); ?>">
                    <?=HTML::chars($event['title']); ?>
                </a>
                &nbsp;
                <a target="blank" href="<?= URL::site('/event/'. $event['id'].'-'.$event['url_title']); ?>">
                    <i class="i-external_link"></i>
                </a>
            </td>
            <td>
                <span class="nobr"><?= HelpingStuff::humanisedate($event['date']); ?></span>
            </td>
            <td>
                <?= HTML::chars($event['username']); ?>
            </td>
            <td>
                <a href="<?php echo URL::site('admin/event/delete/'. $event['id']); ?>">
                    Удалить
                </a>
            </td>
            
        </tr>
    <?php endforeach; ?>
    </table>
<?php  echo $pagination; ?>