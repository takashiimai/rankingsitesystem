<?php include "parts_admin_menu.php"; ?>

<div class="container">
    <div class="columns">
        <div class="column is-half">
            <div class="title">
                種別管理
            </div>
        </div>
        <div class="column is-half has-text-right">
            <span class="button">
                <a href="/admin_category/edit/">新規作成</a>
            </span>
        </div>
    </div>

<?php if (empty($lists)): ?>
    <div class="notification is-warning">
        登録されていません。
    </div>
<?php else: ?>

    <table class="table is-striped is-hoverable">
        <thead>
            <tr>
                <th>ID</th>
                <th>種別名</th>
                <th>スラッグ</th>
            </tr>
            <tbody>
<?php foreach ($lists as $row): ?>
                <tr onclick="location.href='/admin_category/edit/<?php echo $row['id']; ?>';" class="pointer">
                    <td><?php echo $row['id'];?></td>
                    <td><?php echo $row['name'];?></td>
                    <td><?php echo $row['slug'];?></td>
                <tr>
<?php endforeach; ?>
            </tbody>

<?php endif; ?>

</div>

