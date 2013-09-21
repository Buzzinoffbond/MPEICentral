<h1>Список всех статей</h1>
<h3><?php if(isset($message))
echo $message; ?></h3>
    <table class="table-list">
            <tr>
            <th>
                Название
            </th>
            <th>
                Дата публикации
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
                <a href="<?php echo URL::site('admin/article/edit/'. $article['id']); ?>">
                    <?=HTML::chars($article['title']); ?>
                </a>
                &nbsp;
                <a target="blank" href="<?php echo URL::site('/articles/'. $article['id'].'/'.$article['url_title']); ?>">
                    <i class="i-external_link"></i>
                </a>
            </td>
            <td>
                <span class="nobr"><?= HelpingStuff::humanisedate($article['date']); ?></span>
            </td>
            <td>
                <?= HTML::chars($article['username']); ?>
            </td>
            <td>
                <a href="<?php echo URL::site('admin/article/delete/'. $article['id']); ?>">
                    Удалить
                </a>
            </td>
            
        </tr>
    <?php endforeach; ?>
    </table>
<?php  echo $pagination; ?>