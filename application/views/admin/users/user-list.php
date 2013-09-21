<h1>Список пользователей</h1>
<h3><?php if(isset($message))
echo $message; ?></h3>
    <table class="table-list">
            <tr>
                <th id="avatars">
                    
                </th>
                <th id="usernames">
                    Логин
                </th>
                <th id="real_names">
                    Имя
                </th>

        </tr>
    <?php foreach($users as $user): ?>
        <tr>
            <td>
                <a href="<?= URL::site('admin/user/edit/'.$user['username']); ?>">
                    <img class="userpic-bar" src="<?= URL::site('public/images/userpics/'.$user['userpic']); ?>">
                </a>
            </td>
            <td>
                <a href="<?= URL::site('admin/user/edit/'.$user['username']); ?>">
                    <?= HTML::chars($user['username']); ?>
                </a>
            </td>
            <td>
                <?= HTML::chars($user['real_name']); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php  echo $pagination; ?>