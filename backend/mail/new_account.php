<?= $this->render('_mailheader', ['lang' => $lang]) ?>
<?php
$host = \Yii::$app->params['website'];
$url = Yii::$app->ManageRequest->getVariable('website') . 'email-updation?auth=' . $model->auth_key . '&email=' . $model->email;
?>
<tr>
    <td style="padding:40px 20px; font-family:'Open Sans',arial, sans-serif; font-size:13px"><p><br/>Hello <?= (isset($model->first_name)) ? $model->first_name . ' ' . $model->last_name . ',' : "there " ?></p>
        <p style=" font-family:'Open Sans',arial, sans-serif; font-size:13px;">Thank you for registering with AGOGO.
        </p>
        <p style=" font-family:'Open Sans',arial, sans-serif; font-size:13px;color: #abaaaa;font-style:italic;"><a href="https://play.google.com/store/apps/details?id=com.wakralab.abrajbay&hl=en"><img src="<?= $host . '/uploads/android-download.png'; ?>"/></a><a href="https://apps.apple.com/us/app/abraj-bay-prospect-tenant/id1487595480"><img src="<?= $host . '/uploads/ios_download.png'; ?>"/></a></p>
        <p style=" font-family:'Open Sans',arial, sans-serif; font-size:13px;color: #abaaaa;font-style:italic;">Thank You</p>
        <p style=" font-family:'Open Sans',arial, sans-serif; font-size:13px;color: #abaaaa;font-style:italic;">Team AGOGO</p>
    </td>
</tr>
<?= $this->render('_mailfooter') ?>