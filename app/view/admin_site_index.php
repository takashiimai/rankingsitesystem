<?php include "parts_admin_menu.php"; ?>

<div class="container">
    <div class="columns">
        <div class="column is-half">
            <div class="title">
                サイト管理
            </div>
        </div>
        <div class="column is-half has-text-right">
            <span class="button">
                <a href="/admin_site/edit/">新規作成</a>
            </span>
        </div>
    </div>
   


<?php if (empty($lists)): ?>
    <div class="notification is-warning">
        登録されていません。
    </div>
<?php else: ?>

    <table class="table is-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>メイン画像</th>
                <th>サイト名</th>
                <th>種別名</th>
                <th>表示順</th>
            </tr>
            <tbody>
<?php foreach ($lists as $row): ?>
                <tr onclick="location.href='/admin_site/edit/<?php echo $row['id']; ?>';" class="pointer">
                    <td><?php echo $row['id'];?></td>
                    <td><img class="image is-48x48" src="<?php echo $row['main_image'];?>"></td>
                    <td><?php echo $row['name'];?></td>
                    <td><?php echo $row['category_name'];?></td>
                    <td><?php echo $row['orderby'];?></td>
                <tr>
<?php endforeach; ?>
            </tbody>

<?php endif; ?>

</div>

