<?php if ($models != NULL) { ?>
    <option value="">Select Area</option>
    <?php foreach ($models as $model) {
        ?>
        <option value="<?php echo $model->id; ?>"><?php echo $model->name_en; ?></option>
    <?php } ?>

<?php } ?>
