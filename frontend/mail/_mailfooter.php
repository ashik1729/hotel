<?php $get_config = \common\models\Settings::find()->where(['status' => 1])->one(); ?>

<tr>
    <td style="background-color:#f7f4f1"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <td width="100%" align="center"  valign="top" style="border-right:solid 1px #d7d7d7; padding-top: 10px; padding-bottom: 20px; width:30%; padding-left: 6px; padding-right: 6px;"><h4 style=" font-family:'Open Sans',arial, sans-serif; font-size:16px; color:#414042; margin-bottom:10px; margin-top: 5px;">Contact Us <span style=" width: 32px; height: 3px; display: block; background: #f57b20; margin: 8px auto 0 auto;"></span></h4>
                        <p style="font-family:'Open Sans',arial, sans-serif; font-size:10px;line-height: 18px">Tel:  <?php echo $get_config->phone_number; ?>.<br>
                            Office:  <?php echo $get_config->additional_phone_number; ?>.<br>
                            <a href="mailto:support@capon.com" style="border:none; color:#414042; text-decoration: none"> <?php echo $get_config->email; ?></a>.<br>

                    </td>

                </tr>
            </tbody>
        </table></td>
</tr>
</body>
</html>