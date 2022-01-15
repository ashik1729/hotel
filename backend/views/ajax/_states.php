<?php if ($models != NULL) { ?>
    <option value="">Select State</option>
    <?php foreach ($models as $model) {
        ?>
        <option value="<?php echo $model->id; ?>"><?php echo $model->state_name; ?></option>
    <?php } ?>

<?php } ?>
