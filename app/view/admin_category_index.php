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

<?php echo $lists; ?>

<?php endif; ?>

</div>

