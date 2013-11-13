<h1>Список всех конкурсов</h1>
<h3><?php if(isset($message))
echo $message; ?></h3>
    <table class="table-list">
            <tr>
            <th>
                Название
            </th>
            <th>
            	Статус
            </th>
                        
        </tr>
    <?php foreach($contests as $contest): ?>

        <tr>
            <td>
                <a href="<?= URL::site('admin/contest/edit/'. $contest['id'].'-'.$contest['url_title']); ?>">
                    <?=HTML::chars($contest['title']); ?>
                </a>
                &nbsp;
                <a target="blank" href="<?= URL::site('/contest/'. $contest['id'].'-'.$contest['url_title']); ?>">
                    <i class="i-external_link"></i>
                </a>
            </td>
            <td>
            	<?php if ($contest['active']==TRUE){ echo "Активен";}else{echo "Неактивен";} ?>
            </td>                        
        </tr>
    <?php endforeach; ?>
    </table>
<?php  echo $pagination; ?>