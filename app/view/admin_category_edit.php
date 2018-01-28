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
                <a href="/admin_category/post/">キャンセル</a>
            </span>
        </div>
    </div>

    <div class="error"></div>

    <form method="post" action="/admin_category/edit_post">
        <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
        <div class="columns">
            <div class="column is-one-quarter">
                種別ID
            </div>
            <div class="column is-three-quarters">
<?php if ($post['id'] > 0): ?>
                <?php echo $post['id']; ?>
<?php else: ?>
                新規登録
<?php endif; ?>
            </div>
        </div>

        <div class="columns">
            <div class="column is-one-quarter">
                種別名
            </div>
            <div class="column is-three-quarters">
                <div class="control">
                    <input class="input" type="text" name="name" value="<?php echo $post['name']; ?>">
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column is-one-quarter">
                スラッグ
            </div>
            <div class="column is-three-quarters">
                <div class="control">
                    <input class="input" type="text" name="slug"  value="<?php echo $post['slug']; ?>">
                </div>
            </div>

        </div>

        <div class="control">
            <button type="submit" class="button is-link">登録</button>
        </div>

    </form>
    
</div>


<script>

$(function(){

    $('form').submit(function(event) {

        // HTMLでの送信をキャンセル
        event.preventDefault();

        $('button[type="submit"]').addClass("is-loading");
 
       // 操作対象のフォーム要素を取得
       var $form = $(this);
 
        // 送信
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: $form.serialize(),
            dataType: 'json',
            timeout: 10000,  // 単位はミリ秒
        }).done(function(data,textStatus,XHR) {
            if (data['status'] == 'ERROR') {
                $('div.error').html(data['html']);
            } else {
/*
                var html = '<div class="notification is-info" style="margin-bottom:40px">登録しました。</div>';
                $('div.error').html(html);
*/
                alert("登録しました");
                location.href = '/admin_category/edit/' + data['id'];
            }
        }).fail(function(xhr, textStatus, errorThrown) {
            console.log(xhr);
alert("error");
        }).always(function() {
            $('button[type="submit"]').removeClass("is-loading");
        });

    });

});


</script>

