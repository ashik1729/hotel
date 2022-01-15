<?php if ($models != NULL) { ?>
    <select id="banner-map_to" class="form-control" name="Banner[map_to]" aria-required="true" aria-invalid="false">
        <option value="">Select Products</option>
        <?php foreach ($models as $model) {
            ?>
            <option value="<?php echo $model->id; ?>"><?php echo $model->product_name_en; ?></option>
        <?php } ?>
    </select>

<?php } ?>
