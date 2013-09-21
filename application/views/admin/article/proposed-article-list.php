<h1>Список предложенных статей</h1>
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
    <?php foreach($articles as $article): ?>

        <tr>
            <td>
                <a href="<?php echo URL::site('admin/article/proposed/'. $article['id']); ?>">
                    <?=HTML::chars($article['title']); ?>
                </a>
            </td>
            <td>
                <span class="nobr"><?= HelpingStuff::humanisedate($article['date']); ?></span>
            </td>
            <td>
                <?= HTML::chars($article['username']); ?>
            </td>
            <td>
                <a href="<?php echo URL::site('admin/article/proposed/?delete='. $article['id']); ?>">
                    Удалить
                </a>
            </td>
            
        </tr>
    <?php endforeach; ?>
    </table>
<?php  echo $pagination; ?>