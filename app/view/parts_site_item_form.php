<?php foreach ($config_site_items as $row): ?>
<div class="columns">
    <div class="column is-one-quarter">
        <?php echo $row['name']; ?>[<?php echo $row['slug']; ?>]
    </div>
    <div class="column is-three-quarters">
        <div class="control">
<!--            <input class="input" type="text" name="site_item[<?php echo $row['slug']; ?>]"  value="<?php echo isset($site_items[ $row['slug'] ]['meta']) ? $site_items[ $row['slug'] ]['meta'] : ''; ?>">-->
            <textarea class="textarea" name="site_item[<?php echo $row['slug']; ?>]"><?php echo isset($site_items[ $row['slug'] ]['meta']) ? $site_items[ $row['slug'] ]['meta'] : ''; ?></textarea>
        </div>
    </div>
</div>
<?php endforeach; ?>
