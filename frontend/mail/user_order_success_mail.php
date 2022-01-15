<?=
$this->render('_mailheader', ['lang' => $lang]);
?>

<tr>
    <td style="padding:10px 10px 10px 10px; font-style:italic;">
        Hi <?php echo $order->billAddress->first_name; ?> <?php echo $order->billAddress->last_name; ?>,<br/><br/>Greetings from Agogo.com!<br/><br/>
        Thank you for your order from Agogo.com. You can check the status of your order by logging into your account.<br/><br/>
        If you have any questions about your order please contact us at sales@agogo.com or call us at <?php echo Yii::$app->ManageRequest->getMessage('support_number', $lang); ?>
        Monday-Saturday, 9.30am-6.30pm Qatar Time.<br/><br/>
        Your order confirmation is below. Thank you again for your business.<br/><br/>
    </td>
</tr>
<tr><td> <span style="color:#f57a12;font-size: 15px;padding-left:10px;">Your Order # AGOR<?php echo $order->id; ?></span>


    </td></tr>
<br/>

<tr>
    <td>
        <table cellspacing="0" cellpadding="0" border="0" width="776" style="    font-family: 'Open Sans',arial, sans-serif;font-size: 13px;">
            <thead>
                <tr>
                    <th align="left" width="325" bgcolor="#EAEAEA" style="    font-family: 'Open Sans',arial, sans-serif;font-size:13px;padding:5px 9px 6px 9px;line-height:1em">Billing Information:</th>
                    <th width="10"></th>
                    <th align="left" width="325" bgcolor="#EAEAEA" style="font-family:'Open Sans',arial, sans-serif;font-size:13px;padding:5px 9px 6px 9px;line-height:1em">Payment Method:</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td valign="top" style="font-size:13px;padding:9px 9px 9px 9px;border-left:1px solid #eaeaea;border-bottom:1px solid #eaeaea;border-right:1px solid #eaeaea">
                        <?php echo $order->billAddress->first_name; ?>   <?php echo $order->billAddress->last_name; ?>  <br>

                        <?php
                        if ($order->billAddress->streat_address) {
                            echo $order->billAddress->streat_address;
                            ?> <br><?php } ?>
                        <?php echo $order->billAddress->city ? $order->billAddress->city0->name_en : ""; ?><br>
                        <?php echo $order->billAddress->postcode; ?><br>
                        <?php echo $order->billAddress->state ? $order->billAddress->state0->state_name : ""; ?><br>
                        <?php echo $order->billAddress->country ? $order->billAddress->country0->country_name : ""; ?><br>


                    </td>
                    <td>&nbsp;</td>
                    <td valign="top" style="font-family: 'Open Sans',arial, sans-serif;font-size:12px;padding:0px 9px 9px 9px;border-left:1px solid #eaeaea;border-bottom:1px solid #eaeaea;border-right:1px solid #eaeaea">
                        <p style="text-transform: uppercase;font-weight: bold;padding-top:0px;"><?php
                            if ($order->payment_method == 1) {
                                echo "Cash";
                            } elseif ($order->payment_method == 2) {
                                echo "Card";
                            } elseif ($order->payment_method == 3) {
                                echo "Online";
                            } else {
                                echo "";
                            }
                            ?></p>

                        <?php if (($order->transaction_id != 0)) { ?>
                            <p style="color:#f57a12;font-size: 15px;">Transaction ID:<?php echo $order->transaction_id; ?> </p>
                        <?php } ?>

                    </td>
                </tr>
            </tbody>
        </table>
        <br>

        <table cellspacing="0" cellpadding="0" border="0" width="776" style="    font-family: 'Open Sans',arial, sans-serif;font-size: 13px;">
            <thead>
                <tr>
                    <th align="left" width="364" bgcolor="#EAEAEA" style="font-family:'Open Sans',arial, sans-serif;font-size:13px;padding:5px 9px 6px 9px;line-height:1em">Shipping Information:</th>
                    <th width="10"></th>
                    <th align="left" width="364" bgcolor="#EAEAEA" style="font-family:'Open Sans',arial, sans-serif;font-size:13px;padding:5px 9px 6px 9px;line-height:1em">Shipping Method:</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td valign="top" style="font-size:13px;padding:7px 9px 9px 9px;border-left:1px solid #eaeaea;border-bottom:1px solid #eaeaea;border-right:1px solid #eaeaea">
                        <?php echo $order->shipAddress->first_name; ?>   <?php echo $order->shipAddress->last_name; ?>  <br>

                        <?php
                        if ($order->shipAddress->streat_address) {
                            echo $order->shipAddress->streat_address;
                            ?> <br><?php } ?>
                        <?php echo $order->shipAddress->city ? $order->shipAddress->city0->name_en : ""; ?><br>
                        <?php echo $order->shipAddress->postcode; ?><br>
                        <?php echo $order->shipAddress->state ? $order->shipAddress->state0->state_name : ""; ?><br>
                        <?php echo $order->shipAddress->country ? $order->shipAddress->country0->country_name : ""; ?><br>
                        &nbsp;
                    </td>
                    <td>&nbsp;</td>
                    <td valign="top" style="font-size:13px;padding:7px 9px 9px 9px;border-left:1px solid #eaeaea;border-bottom:1px solid #eaeaea;border-right:1px solid #eaeaea">
                        <?php
                        if ($order->shipping_method == 1) {
                            echo "Delivery";
                        } else {
                            ?>
                            <?php
                            echo "Pickup From Store";
                            ?>
                            Shipping Rate:<?php
                            echo Yii::$app->Currency->convert($order->shipping_charge, $order->store);
                        }
                        ?>
                        &nbsp;
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <table cellspacing="0" cellpadding="0" border="0" width="776" style="border:1px solid #eaeaea;font-family: 'Open Sans',arial, sans-serif;">
            <thead>
                <tr>
                    <th align="left" bgcolor="#EAEAEA" style="font-size:13px;padding:3px 9px">Item</th>
                    <th align="left" bgcolor="#EAEAEA" style="font-size:13px;padding:3px 9px">Product Code</th>
                    <th align="center" bgcolor="#EAEAEA" style="font-size:13px;padding:3px 9px">Qty</th>
                    <th align="right" bgcolor="#EAEAEA" style="font-size:13px;padding:3px 9px">Subtotal</th>
                </tr>
            </thead>

            <tbody bgcolor="#F6F6F6">
                <?php
                $orderProducts = common\models\OrderProducts::findAll(['order_id' => $order->id]);
                if ($orderProducts != NULL) {
                    foreach ($orderProducts as $orderProduct) {
                        ?>
                        <tr>
                            <td align="left" valign="top" style="font-size:11px;padding:3px 9px;padding-top:10px;">
                                <strong style="font-size:11px;text-transform: uppercase;"><?php echo $orderProduct->product->product_name_en; ?></strong>

                                <?php
                                $get_options = explode(',', $orderProduct->options);
                                if ($get_options != NULL) {
                                    foreach ($get_options as $get_option) {
                                        $option_details = $orderProduct->getAttr($get_option);
                                        if ($option_details != NULL) {
                                            ?>

                                            <dl style="margin:0;padding:0">

                                                <dt><strong><?php echo $option_details->attributesValue->attributes0->name; ?>: </strong>
                                                    <span style="margin:0;padding:0 0 0 9px"><?php echo $option_details->attributesValue->value; ?> </span></dt>

                                            </dl>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            </td>
                            <td align="left" valign="top" style="font-size:11px;padding:3px 9px;padding-top:10px; "><?php echo $orderProduct->product->sku; ?></td>
                            <td align="center" valign="top" style="font-size:11px;padding:3px 9px;padding-top:10px; "><?php echo $orderProduct->quantity; ?></td>
                            <td align="right" valign="top" style="font-size:11px;padding:3px 9px;padding-top:10px; ">

                                <span><?php echo Yii::$app->Currency->convert($orderProduct->amount, $orderProduct->order->store); ?></span>                                        </td>
                        </tr>
                        <?php
                    }
                }
                ?>

                <tr>

                    <td colspan="3" align="right" style="padding:13px 9px 0 0;font-size:13px;">
                        Subtotal                    </td>
                    <td align="right" style="padding:13px 9px 0 0;font-size:13px;">
                        <span><?php echo Yii::$app->Currency->convert(($order->total_amount - $order->shipping_charge), $order->store); ?> </span>                    </td>
                </tr>
                <tr>
                    <td colspan="3" align="right" style="padding:3px 9px;font-size:13px;">
                        Shipping &amp; Handling                    </td>
                    <td align="right" style="padding:3px 9px;font-size:13px;">
                        <span><?php echo Yii::$app->Currency->convert($order->shipping_charge, $order->store); ?></span>                    </td>
                </tr>
                <tr>
                    <td colspan="3" align="right" style="padding:3px 9px 13px 0;font-size:13px;">
                        <strong>Grand Total</strong>
                    </td>
                    <td align="right" style="padding:3px 9px 13px 0;font-size:13px;">
                        <strong><span><?php echo Yii::$app->Currency->convert($order->total_amount, $order->store); ?></span></strong>
                    </td>
                </tr>
            </tbody>
        </table>

        <p style=" font-family:'Open Sans',arial, sans-serif; font-size:13px;font-style:italic;color:#acacb1; text-align: center">* This is an automatically generated email, please do not reply to this email.</p>

    </td>
</tr>


<?= $this->render('_mailfooter') ?>