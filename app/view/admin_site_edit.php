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
                <a href="/admin_site/">キャンセル</a>
            </span>
        </div>
    </div>

    <div class="error"></div>

    <form method="post" action="/admin_site/edit_post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
        <div class="columns">
            <div class="column is-one-quarter">
                サイトID
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
                サイト名
            </div>
            <div class="column is-three-quarters">
                <div class="control">
                    <input class="input" type="text" name="name" value="<?php echo $post['name']; ?>">
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column is-one-quarter">
                サイト種別
            </div>
            <div class="column is-three-quarters">
                <div class="control">
                    <div class="select">
                        <select name="category_id">
<?php foreach ($categorys as $row): ?>
                            <option value="<?php echo $row['id']; ?>" <?php echo $row['id'] == $post['category_id'] ? 'selected' : ''; ?>><?php echo $row['name']; ?></option>
<?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column is-one-quarter">
                URL
            </div>
            <div class="column is-three-quarters">
                <div class="control">
                    <input class="input" type="text" name="url"  value="<?php echo $post['url']; ?>">
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column is-one-quarter">
                メイン画像
            </div>
            <div class="column is-three-quarters">
                <div class="control">
                    <input type="hidden" name="main_image"  value="<?php echo $post['main_image']; ?>">
                    <label for="upload_file">
                        <span class="button is-primary">写真を選択</span>
                        <input id="upload_file" class="input" type="file" name="upload_file" accept=".jpg,.gif,.png,image/gif,image/jpeg,image/png" style="display:none">
                    </label>
                    <figure id="upload_file_thumb" class="image is-128x128">
<?php if ($post['main_image']): ?>
                        <img data-type="main_image" src="<?php echo $post['main_image']; ?>">
<?php endif; ?>
                    </figure>
                </div>
            </div>
        </div>

        <div id="area_site_items_form"></div>

        <div class="control">
            <button type="submit" class="button is-link">登録</button>
        </div>

    </form>
    
</div>

<script src="/js/jquery.uploadThumbs.js"></script>
<script>

$(function(){
<?php if ($post['category_id'] > 0): ?>
    refresh_config_site_item(<?php echo $post['category_id']; ?>);
<?php endif; ?>


    $('form').submit(function(event) {
        // HTMLでの送信をキャンセル
        event.preventDefault();

        $('button[type="submit"]').addClass("is-loading");
 
        // 操作対象のフォーム要素を取得
        var $form = $(this);
        var formData = new FormData($(this).get(0));
 
        // 送信
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
//            data: $form.serialize(),
            processData: false,
            contentType: false,
            data: formData,
            dataType: 'json',
            timeout: 10000,  // 単位はミリ秒
        }).done(function(data,textStatus,XHR) {
            if (data['status'] == 'ERROR') {
                $('div.error').html(data['html']);
            } else {
                alert("登録しました");
                location.href = '/admin_site/edit/' + data['id'];
            }
        }).fail(function(xhr, textStatus, errorThrown) {
            console.log(xhr);
            alert("エラーが発生しました");
        }).always(function() {
            $('button[type="submit"]').removeClass("is-loading");
        });

    });

    $(document).on('change', 'input[name="upload_file"]', function(event) {
        $('[data-type="main_image"]').remove();
        $('input[name="main_image"]').val("");
    });


    $(document).on('change', 'select[name="category_id"]', function(event) {
        var v = $('select[name="category_id"]').val();
        refresh_config_site_item(v);
    });

    $('input#upload_file').uploadThumbs({
        position : '#upload_file_thumb', 
    });
});

function refresh_config_site_item(category_id) {
    $.ajax({
        url: '/admin_site/get_config_site_item',
        type: 'post',
        data: {
            id:  $('form input[name="id"]').val(),
            category_id:  category_id,
        },
        dataType: 'html',
        timeout: 10000,  // 単位はミリ秒
    }).done(function(data,textStatus,XHR) {
        $('#area_site_items_form').html(data);
    }).fail(function(xhr, textStatus, errorThrown) {
        console.log(xhr);
        alert("エラーが発生しました");
    }).always(function() {
        $('button[type="submit"]').removeClass("is-loading");
    });
}

</script>

