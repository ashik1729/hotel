<?php if ($models != NULL) { ?>
    <option value="">Choose a Attribute Value</option>
    <?php foreach ($models as $model) {
        ?>
        <option value="<?php echo $model->id; ?>"><?php echo $model->value; ?></option>
    <?php } ?>

<?php } ?>
