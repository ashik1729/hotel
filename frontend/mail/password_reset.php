<?= $this->render('_mailheader', ['lang' => $lang]);
use yii\helpers\Html;
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $model->password_reset_token]);
?>
<tr>
    <td style="padding:40px 20px; font-family:'Open Sans',arial, sans-serif; font-size:13px">
        <p><br />Hello <?= (isset($model->first_namw)) ? $model->first_namw  . ',' : "there " ?></p>
        <p style=" font-family:'Open Sans',arial, sans-serif; font-size:13px;">Follow the Code below to reset your password:</p>

        <p style=" font-family:'Open Sans',arial, sans-serif; font-size:13px;">Follow the link below to reset your password:</p>

        <p style=" font-family:'Open Sans',arial, sans-serif; font-size:13px;">><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
       
      
        <p style=" font-family:'Open Sans',arial, sans-serif; font-size:13px;color: #abaaaa;font-style:italic;">Thank You</p>
        <p style=" font-family:'Open Sans',arial, sans-serif; font-size:13px;color: #abaaaa;font-style:italic;">HCCA</p>
    </td>
</tr>
<?= $this->render('_mailfooter') ?>