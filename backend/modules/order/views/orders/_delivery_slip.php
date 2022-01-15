<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <div id="fb-root"></div>
        <div style="margin:auto; width:776px;  margin-top:40px; margin-bottom:40px;">
            <h3 style=" font-family: 'Open Sans',arial, sans-serif;" class="mb-2">Billing Address</h3>
            <table cellspacing="0" cellpadding="0" border="0" width="776" style="    border: 1px solid #c0c0c0; margin-bottom: 30px;    font-family: 'Open Sans',arial, sans-serif;font-size: 13px;">

                <tbody>
                    <tr>


                        <td valign="top" style="font-size:20px;padding:9px 9px 9px 9px;">
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
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <h3 style=" font-family: 'Open Sans',arial, sans-serif;" class="mb-2">Shipping Address</h3>
            <table cellspacing="0" cellpadding="0" border="0" width="776" style=" border: 1px solid #c0c0c0;   font-family: 'Open Sans',arial, sans-serif;font-size: 13px;">

                <tbody>
                    <tr>



                        <td valign="top" style="font-size:20px;padding:9px 9px 9px 9px;">
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
        </div>
<!--    <tocpagebreak />-->


    </body>
</html>