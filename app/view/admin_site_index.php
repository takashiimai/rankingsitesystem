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

    <table class="table is-striped is-hoverable sortable">
        <thead>
            <tr>
                <th>ID</th>
                <th>メイン画像</th>
                <th>サイト名</th>
                <th>種別名</th>
            </tr>
        </thead>
        <tbody>
<?php foreach ($lists as $row): ?>
            <tr id="<?php echo $row['id'];?>" onclick="location.href='/admin_site/edit/<?php echo $row['id']; ?>';" class="pointer">
                <td><?php echo $row['id'];?></td>
                <td><img class="image is-48x48" src="<?php echo $row['main_image'];?>"></td>
                <td><?php echo $row['name'];?></td>
                <td><?php echo $row['category_name'];?></td>
            </tr>
<?php endforeach; ?>
        </tbody>
    </table>

<?php endif; ?>

</div>


<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
    $(function(){

        $("table.sortable tbody").sortable({
            update: function(){
                var orderby = [
<?php
    $orderby = array();
    foreach ($lists as $row) {
        $orderby[] = $row['orderby'];
    }
    echo implode(",", $orderby);
?>                
                ];
                var id = $(this).sortable("toArray");

                // 送信
                $.ajax({
                    url: '/admin_site/change_orderby_site',
                    type: 'post',
                    data: {
                        orderby: orderby,
                        id: id,
                    },
                    dataType: 'json',
                    timeout: 10000,  // 単位はミリ秒
                }).done(function(data,textStatus,XHR) {
                    if (data['status'] == 'ERROR') {
                        $('div.error').html(data['html']);
                    }
                }).fail(function(xhr, textStatus, errorThrown) {
                    console.log(xhr);
                    alert("エラーが発生しました");
                }).always(function() {
                });



            }
        });


    });
</script>







