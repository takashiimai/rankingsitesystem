<?php
    function disp_view($templete, $site) {
        $replace = array();
        $site_keys = array(
            'name',
            'url',
            'main_image',
        );
        foreach ($site_keys as $key) {
            $replace[ '{{'.$key.'}}' ] = $site[ $key ];
        }

        $site_item_keys = array_keys($site['items']);
        foreach ($site_item_keys as $key) {
            $replace[ '{{'.$key.'}}' ] = isset($site['items'][ $key ]['meta']) ? $site['items'][ $key ]['meta'] : '';
        }
        return str_replace(array_keys($replace), array_values($replace), $templete);
    }

?>

<div class="container">

    <div class="columns">
        <div class="column">
            <div class="title has-text-centered">
                <?php echo $category['name']; ?>のランキング
            </div>
        </div>
    </div>
   

<?php if (empty($sites)): ?>

<?php else: ?>

<?php foreach ($sites as $site): ?>

    <div class="columns">

        <div class="column is-one-quarter is-hidden-touch">&nbsp;</div>
        
        <div class="column">
            <?php echo disp_view($category['templete'], $site); ?>
        </div>

        <div class="column is-one-quarter is-hidden-touch">&nbsp;</div>

    </div>


<?php endforeach; ?>

<?php endif; ?>

</div>

