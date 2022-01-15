<?php if ($models != NULL) { ?>
    <?php foreach ($models as $model) {
        ?>

        <div class="row p-3 ptb-0">
            <?php if ($model['attr_items'] != NULL) { ?>
                <div class="col-sm-12">
                    <h6><?php echo $model['attribute_name']; ?></h6>
                </div>
                <?php foreach ($model['attr_items'] as $att_item) {
                    ?>
                    <div class="col-sm-3">
                        <label class="action_label">
                            <input type="radio" id="css" required="" name="OrderProducts[attribute][<?php echo $model['attribute_id']; ?>]" value="<?= $att_item['id']; ?>"> &nbsp;&nbsp;<?= $att_item['attributes_value']; ?>
                        </label>
                        <br>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    <?php } ?>
<?php } ?>
