<html>
    <head>
        <title><?= Yii::$app->ManageRequest->getVariable('title_ar'); ?> </title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <div id="fb-root"></div>

        <?php if ($merchant_list != NULL) { ?>
            <?php
            foreach ($merchant_list as $merchant_id) {
                $merchant = common\models\Merchant::findOne(['id' => $merchant_id]);
                $orderInvoice = common\models\OrderInvoice::findOne(['merchant_id' => $merchant_id, 'order_id' => $order->id]);
                ?>


                <div style="margin:auto; width:776px; border:solid 1px #c0c0c0; margin-top:40px; margin-bottom:40px;">
                    <table id="Table_01" width="776" border="0" cellpadding="0" cellspacing="0" align="center">
                        <tr>
                            <td><a href="<?= Yii::$app->ManageRequest->getVariable('website'); ?>"><img src="<?= Yii::$app->ManageRequest->getVariable('website'); ?>/images/email_head.jpg" width="776" height="102" alt=""></a></td>
                        </tr>
                        <tr><td  width="776" style="padding-bottom: 20px"> <span style="font-size: 20px;font-weight: bold;padding-left:10px;padding-bottom: 20px;font-family: 'Open Sans',arial, sans-serif;">Invoice Number #<?php echo $orderInvoice ? $orderInvoice->invoice : ""; ?></span>
                            </td>
                        </tr>
                        <br>

                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" border="0" width="776" style="    font-family: 'Open Sans',arial, sans-serif;font-size: 13px;">
                                    <thead>
                                        <tr>
                                            <th align="left" width="383" bgcolor="#EAEAEA" style="    font-family: 'Open Sans',arial, sans-serif;font-size:13px;padding:5px 9px 6px 9px;line-height:1em">Sold By :</th>
                                            <th width="10"></th>
                                            <th align="left" width="383" bgcolor="#EAEAEA" style="font-family:'Open Sans',arial, sans-serif;font-size:13px;padding:5px 9px 6px 9px;line-height:1em">Billing Information:</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>

                                            <td valign="top" style="font-size:13px;padding:9px 9px 9px 9px;border-left:1px solid #eaeaea;border-bottom:1px solid #eaeaea;border-right:1px solid #eaeaea">


                                                <?php echo $merchant->business_name; ?>
                                                <?php
                                                if ($merchant->address) {
                                                    echo $merchant->address;
                                                    ?> <br><?php } ?>
                                                <?php echo $merchant->city ? $merchant->city0->name_en : ""; ?><br>
                                                <?php echo $merchant->state ? $merchant->state0->state_name : ""; ?><br>
                                                <?php echo $merchant->country ? $merchant->country0->country_name : ""; ?><br>
                                            </td>
                                            <td>&nbsp;</td>

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
                                        </tr>
                                    </tbody>
                                </table>
                                <br>
                                <table cellspacing="0" cellpadding="0" border="0" width="776" style="    font-family: 'Open Sans',arial, sans-serif;font-size: 13px;">
                                    <thead>
                                        <tr>
                                            <th align="left" width="383" bgcolor="#EAEAEA" style="    font-family: 'Open Sans',arial, sans-serif;font-size:13px;padding:5px 9px 6px 9px;line-height:1em">Invoice Details</th>
                                            <th width="10"></th>
                                            <th align="left" width="383" bgcolor="#EAEAEA" style="font-family:'Open Sans',arial, sans-serif;font-size:13px;padding:5px 9px 6px 9px;line-height:1em">Shipping Information:</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>

                                            <td valign="top" style="font-size:13px;padding:9px 9px 9px 9px;border-left:1px solid #eaeaea;border-bottom:1px solid #eaeaea;border-right:1px solid #eaeaea">
                                                <p style="font-weight: bold;font-size: 15px;">Invoice Number : #<span><?php echo $orderInvoice->invoice; ?></span></p>
                                                <p style="font-weight: bold;font-size: 15px;">Invoice Date : <?php echo date('Y-m-d', strtotime($orderInvoice->invoice_date)); ?></p>
                                                <p style="font-weight: bold;font-size: 15px;">Order Number : #AGOR<span><?php echo $order->id; ?></span></p>
                                                <p style="font-weight: bold;font-size: 15px;">Order Date : <?php echo date('Y-m-d', strtotime($order->created_at)); ?></p>
                                            </td>
                                            <td>&nbsp;</td>

                                            <td valign="top" style="font-size:13px;padding:9px 9px 9px 9px;border-left:1px solid #eaeaea;border-bottom:1px solid #eaeaea;border-right:1px solid #eaeaea">
                                                <?php echo $order->shipAddress->first_name; ?>   <?php echo $order->shipAddress->last_name; ?>  <br>
                                                <?php
                                                if ($order->shipAddress->streat_address) {
                                                    echo $order->shipAddress->streat_address;
                                                    ?> <br><?php } ?>
                                                <?php echo $order->shipAddress->city ? $order->shipAddress->city0->name_en : ""; ?><br>
                                                <?php echo $order->shipAddress->postcode; ?><br>
                                                <?php echo $order->shipAddress->state ? $order->shipAddress->state0->state_name : ""; ?><br>
                                                <?php echo $order->shipAddress->country ? $order->shipAddress->country0->country_name : ""; ?><br>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <br>
                                <table cellspacing="0" cellpadding="0" border="0" width="776" style="border:1px solid #eaeaea;font-family: 'Open Sans',arial, sans-serif;">
                                    <thead>
                                        <tr>
                                            <th width="155" align="left" bgcolor="#EAEAEA" style="font-size:13px;padding:3px 9px">Item</th>
                                            <th width="155" align="left" bgcolor="#EAEAEA" style="font-size:13px;padding:3px 9px">Product Code</th>
                                            <th width="155" align="center" bgcolor="#EAEAEA" style="font-size:13px;padding:3px 9px">Unit Price</th>
                                            <th width="155" align="center" bgcolor="#EAEAEA" style="font-size:13px;padding:3px 9px">Qty</th>
                                            <th width="156" align="right" bgcolor="#EAEAEA" style="font-size:13px;padding:3px 9px">Subtotal</th>
                                        </tr>
                                    </thead>

                                    <tbody bgcolor="">

                                        <?php
                                        $orderProductsQuery = common\models\OrderProducts::find()->where(['order_id' => $order->id, 'merchant_id' => $merchant->id]);

                                        $orderProducts = $orderProductsQuery->all();
                                        if ($orderProducts != NULL) {
                                            $i = 1;
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
                                                    <td align="center" valign="top" style="font-size:11px;padding:3px 9px;padding-top:10px; "><?php echo $orderProduct->amount; ?></td>
                                                    <td align="center" valign="top" style="font-size:11px;padding:3px 9px;padding-top:10px; "><?php echo $orderProduct->quantity; ?></td>
                                                    <td align="right" valign="top" style="font-size:11px;padding:3px 9px;padding-top:10px; ">

                                                        <span><?php echo Yii::$app->Currency->convert($orderProduct->amount * $orderProduct->quantity, $orderProduct->order->store); ?></span>                                        </td>
                                                </tr>

                                            <?php } ?>
                                        <?php } ?>

                                        <tr>

                                            <td colspan="4" align="right" style="padding:13px 9px 0 0;font-size:13px;">
                                                Subtotal                    </td>
                                            <td align="right" style="padding:13px 9px 0 0;font-size:13px;">
                                                <span><?php echo Yii::$app->Currency->convert(Yii::$app->Order->Subtotal($order->id, $merchant->id), $order->store); ?> </span>                    </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" align="right" style="padding:3px 9px;font-size:13px;">
                                                Shipping &amp; Handling                    </td>
                                            <td align="right" style="padding:3px 9px;font-size:13px;">
                                                <span><?php echo Yii::$app->Currency->convert(Yii::$app->Order->Shipping($order->id, $merchant->id), $order->store); ?></span>                    </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" align="right" style="padding:3px 9px 13px 0;font-size:13px;">
                                                <strong>Grand Total</strong>
                                            </td>
                                            <td align="right" style="padding:3px 9px 13px 0;font-size:13px;">
                                                <strong><span><?php echo Yii::$app->Currency->convert(Yii::$app->Order->Grandtotal($order->id, $merchant->id), $order->store); ?></span></strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>


                            </td>
                        </tr>

                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" border="0" width="776" style="    font-family: 'Open Sans',arial, sans-serif;font-size: 13px;">

                                    <tbody>
                                        <tr>

                                            <td align="center" width="370" valign="middle" style="font-size:13px;padding:9px 9px 9px 9px;border-left:1px solid #eaeaea;border-bottom:1px solid #eaeaea;border-right:1px solid #eaeaea">
                                                <h4 style=" font-family:'Open Sans',arial, sans-serif; font-size:16px; color:#414042; margin-bottom:10px; margin-top: 5px;">Contact Us <span style=" width: 32px; height: 3px; display: block; background: #f57b20; margin: 8px auto 0 auto;"></span></h4>
                                                <?php if ($merchant != NULL) { ?>

                                                    <p style="font-family:'Open Sans',arial, sans-serif; font-size:10px;line-height: 18px">Tel: <?php echo $merchant->mobile_number; ?>.<br>
                                                        Office:  +974 6534425.<br>
                                                        <a href="mailto:<?php echo $merchant->email; ?>" style="border:none; color:#414042; text-decoration: none"> <?php echo $merchant->email; ?></a>.<br>

                                                    <?php } ?>
                                            </td>


                                            <td align="center" width="388" valign="top" style="font-size:13px;padding:9px 9px 9px 9px;border-left:1px solid #eaeaea;border-bottom:1px solid #eaeaea;border-right:1px solid #eaeaea">
                                                <p style="text-align: center">For Agogo - </p><br/>
                                                <?php if ($merchant != NULL) { ?>
                                                    <img src="<?= Yii::$app->ManageRequest->getVariable('website'); ?>/<?php echo 'uploads/merchant/' . $merchant->id . '/signature/' . $merchant->signature; ?>" width="388" height="102" alt="">
                                                    <p style="text-align: center">Authorised Signature</p>
                                                <?php } ?>


                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <p style=" font-family:'Open Sans',arial, sans-serif; font-size:13px;font-style:italic;color:#acacb1; text-align: center">* This is an automatically generated email, please do not reply to this email.</p>

                </div>

                <?php if (count($orderProducts) > $i) { ?>
                <pagebreak/>
            <?php } ?>
            <?php
            $i++;
        }
    }
    ?>

<!--    <tocpagebreak />-->


</body>
</html>