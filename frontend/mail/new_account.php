<?= $this->render('_mailheader', ['lang' => $lang]) ?>
<?php

use yii\helpers\Html;

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $model->auth_key]);
?>
<tr>
    <td style="padding:40px 20px; font-family:'Open Sans',arial, sans-serif; font-size:13px">
        <p><br />Hello <?= (isset($model->first_name)) ? $model->first_name . ',' : "there " ?></p>
        <p style=" font-family:'Open Sans',arial, sans-serif; font-size:13px;">Thank you for registering with HCCA.
        </p>
        <p>Follow the link below to verify your email:</p>
        <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
        <p style=" font-family:'Open Sans',arial, sans-serif; font-size:13px;color: #abaaaa;font-style:italic;">Thank You</p>
        <p style=" font-family:'Open Sans',arial, sans-serif; font-size:13px;color: #abaaaa;font-style:italic;">Team HCCA</p>
    </td>
</tr>
<?= $this->render('_mailfooter') ?>