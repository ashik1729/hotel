<?php if ($models != NULL) { ?>
    <select id="banner-map_to" class="form-control" name="Banner[map_to]" aria-required="true" aria-invalid="false">
        <option value="">Select Merchant</option>
        <?php foreach ($models as $model) {
            ?>
            <option value="<?php echo $model->id; ?>"><?php echo $model->first_name . ' ' . $model->first_name . ' (' . $model->business_name . ' - ' . $model->email . ')'; ?></option>
        <?php } ?>
    </select>

<?php } ?>
