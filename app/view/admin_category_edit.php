<?php include "parts_admin_menu.php"; ?>

    <div class="container">
        <div class="columns">
            <div class="column is-half">
                <div class="title">
                    種別管理
                </div>
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

<?php if ($post['id'] > 0): ?>
        <h2 class="subtitle" style="margin-top:30px">サイト登録アイテム</h2>
        <div class="columns" id="site_item">
            <div class="column">
                <div class="field has-addons">
                    <p class="control">
                        <input class="input" type="text" name="name" placeholder="アイテムタグ名称">
                    </p>
                    <p class="control">
                        <input class="input" type="text" name="slug" placeholder="スラッグ" maxlength="100">
                    </p>
                    <p class="control">
                        <a data-action="add-item" class="button is-link">
                            追加
                        </a>
                    </p>
                </div>
                <div>
                   <p class="help">※アイテムタグ名称は日本語での入力が可能です。</p>
                   <p class="help">※スラッグは半角英数４文字以上で入力してください。重複は不可です。</p>
                </div>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>アイテムタグ名称</th>
                    <th>スラッグ</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
<?php foreach ($site_item_lists as $row): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['slug']; ?></td>
                    <td><button class="button" onclick="delete_site_item(<?php echo $row['id']; ?>)">削除</button></td>
                </tr>                    
<?php endforeach; ?>
            </tbody>
        </table>
<?php endif; ?>
    
    </div>


    <script>

    $(function(){

        $(document).on('click', 'a[data-action="add-item"]', function(event) {
            // 送信
            $.ajax({
                url: '/admin_category/add_site_item',
                type: 'post',
                data: {
                    category_id: $('input[name="id"]').val(),
                    name: $('#site_item input[name="name"]').val(),
                    slug: $('#site_item input[name="slug"]').val(),
                },
                dataType: 'json',
                timeout: 10000,  // 単位はミリ秒
            }).done(function(data,textStatus,XHR) {
                if (data['status'] == 'ERROR') {
                    $('div.error').html(data['html']);
                } else {
                    alert("登録しました");
                    location.href = '/admin_category/edit/' + $('input[name="id"]').val();
                }
            }).fail(function(xhr, textStatus, errorThrown) {
                console.log(xhr);
                alert("エラーが発生しました");
            }).always(function() {
                $('button[type="submit"]').removeClass("is-loading");
            });
        });

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
                    alert("登録しました");
                    location.href = '/admin_category/edit/' + data['id'];
                }
            }).fail(function(xhr, textStatus, errorThrown) {
                console.log(xhr);
                alert("エラーが発生しました");
            }).always(function() {
                $('button[type="submit"]').removeClass("is-loading");
            });

        });

    });


    </script>

