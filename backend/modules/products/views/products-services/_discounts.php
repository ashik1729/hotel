<?php if ($models != NULL) { ?>
    <option value="">Choose a Discount</option>
    <?php
    foreach ($models as $get_discount) {
        ?>
        <option value="<?php echo $get_discount->id; ?>"><?php echo $get_discount->title . ' - ' . ($get_discount->discount_type == 1 ? "Flat (" . $get_discount->discount_rate . ") " : " Percantage (" . $get_discount->discount_rate . "%)"); ?></option>
    <?php } ?>

<?php } ?>
