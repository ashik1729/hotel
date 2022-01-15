<?php if ($models != NULL) { ?>
    <select id="banner-map_to" class="form-control" name="Banner[map_to]" aria-required="true" aria-invalid="false">
        <option value="">Select Category</option>
        <?php foreach ($models as $key => $value) {
            ?>
            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
        <?php } ?>
    </select>

<?php } ?>
