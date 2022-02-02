<?php
echo $this->render('account-menu',['active'=>'dashboard']); ?>
<section class="my-account-detials">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>My Account</h1>
                <div class="account_name">
                    <div class="name">Hello <span><?= Yii::$app->user->identity->first_name; ?></span></div>
                </div>
                <p>
                    From your account dashboard you can view your <a href="<?php echo Yii::$app->request->baseUrl; ?>/package-history">Tour packages history</a>
                </p>
                <p>
                    Manage your <a href="<?php echo Yii::$app->request->baseUrl; ?>/user-address">Address</a>
                </p>
                <p>
                    Change your <a href="<?php echo Yii::$app->request->baseUrl; ?>/reset-password">Password</a>
                </p>
            </div>
        </div>
    </div>
</section>