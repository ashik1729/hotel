<?php
/* @var $this \yii\web\View */
/* @var $content string */

use common\models\CmsContent;
use common\models\Settings;
use frontend\models\LoginForm;
use common\models\User;
use common\models\Users;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use frontend\models\PasswordResetRequestForm;
use nirvana\instafeed\Instafeed;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="<?php echo Yii::$app->request->baseUrl; ?>/images/icons/favicon.ico" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HCCA | Explore Your Life Travel in Dubai</title>

    <meta property="og:image" content="" />

    <link rel="icon" href="<?php echo Yii::$app->request->baseUrl; ?>/images/favicon.png">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>
    <nav class="mobile-menubar">
        <a class="mobile-logo-area" href="<?php echo Yii::$app->request->baseUrl; ?>"><img class="mobile-nav-logo" src="<?php echo Yii::$app->request->baseUrl; ?>/images/hcca-logol.png" alt="HCCA Group Logo"></a>
        <button class="btn btn-menu-close">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
        <ul class="mobile-menu d-flex align-items-center justify-content-center flex-column">
            <li class="menu-item"><a href="<?php echo Yii::$app->request->baseUrl; ?>">Home</a></li>
            <li class="menu-item"><a href="<?php echo Yii::$app->request->baseUrl; ?>/packages">Packages</a></li>
            <li class="menu-item"><a href="<?php echo Yii::$app->request->baseUrl; ?>/visa">Visa</a></li>
            <li class="menu-item"><a href="<?php echo Yii::$app->request->baseUrl; ?>/accomodation">Accommodation</a></li>
            <li class="menu-item"><a href="<?php echo Yii::$app->request->baseUrl; ?>/rent-car">Rent a car</a></li>
            <li class="menu-item"><a href="<?php echo Yii::$app->request->baseUrl; ?>/about-us">About us</a></li>
            <li class="menu-item"><a href="<?php echo Yii::$app->request->baseUrl; ?>/contact-us">Contact us</a></li>
            <?php
            if (!Yii::$app->user->isGuest) { ?>
                <li class="menu-item">
                    <a class="nav-link" href="<?php echo Yii::$app->request->baseUrl; ?>/dashboard">Dashboard </a>
                </li>
                <li class="menu-item">
                    <a class="nav-link" href="<?php echo Yii::$app->request->baseUrl; ?>/log-out"><img src="<?php echo Yii::$app->request->baseUrl; ?>/images/logout.png" alt="HCCA User"></a>
                </li>

            <?php    } else { ?>
                <li class="menu-item">
                    <a class="nav-link" href="" data-bs-toggle="modal" data-bs-target="#LoginEnquiry"><img src="<?php echo Yii::$app->request->baseUrl; ?>/images/user.png" alt="HCCA User"></a>
                </li>
            <?php    }
            ?>

            <!-- <li class="menu-item"><a href="" data-bs-toggle="modal" data-bs-target="#LoginEnquiry"><img src="<?php echo Yii::$app->request->baseUrl; ?>/images/user.png" alt="HCCA User"></a></li> -->
        </ul>
    </nav>

    <header id="home-u">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?php echo Yii::$app->request->baseUrl; ?>"><img src="<?php echo Yii::$app->request->baseUrl; ?>/images/hcca-logol.png" alt="HCCA Group Logo"></a>
                <button class="navbar-toggler btn-menu-open" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon">
                        <img src="<?php echo Yii::$app->request->baseUrl; ?>/images/stroke-miterlimit.svg">
                    </span>
                </button>
                <div class="collapse navbar-collapse menubar" id="navbarSupportedContent">
                    <ul class="navbar-nav ">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo Yii::$app->request->baseUrl; ?>">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo Yii::$app->request->baseUrl; ?>/packages">Packages</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo Yii::$app->request->baseUrl; ?>/visa"> Visa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo Yii::$app->request->baseUrl; ?>/accomodation">Accommodation</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo Yii::$app->request->baseUrl; ?>/rent-car">Rent a car</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo Yii::$app->request->baseUrl; ?>/about-us">About us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo Yii::$app->request->baseUrl; ?>/contact-us">Contact us</a>
                        </li>
                        <?php
                        if (!Yii::$app->user->isGuest) { ?>
                            <li class="nav-item nav-last">
                                <a class="nav-link" href="<?php echo Yii::$app->request->baseUrl; ?>/dashboard">Dashboard </a>
                            </li>
                            <li class="nav-item nav-last">
                                <a class="nav-link" href="<?php echo Yii::$app->request->baseUrl; ?>/log-out"><img src="<?php echo Yii::$app->request->baseUrl; ?>/images/logout.png" alt="HCCA User"></a>
                            </li>

                        <?php    } else { ?>
                            <li class="nav-item nav-last">
                                <a class="nav-link" href="" data-bs-toggle="modal" data-bs-target="#LoginEnquiry"><img src="<?php echo Yii::$app->request->baseUrl; ?>/images/user.png" alt="HCCA User"></a>
                            </li>
                        <?php    }
                        ?>

                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <?= $content ?>
    <?php $footerData =  CmsContent::find()->where(['page_id'=>'about-us'])->one();
    $config = Settings::find()->where(['status' => 1])->one();
  
    ?>

<?php

echo Instafeed::widget([
    'pluginOptions' => [
        'get' => 'user',
        'userId' => '49836101753',     // your Instagram account id, not username!
        'accessToken' => 'IGQVJYRG9kclk5THJrXzI0VXhCTlpiWU5HRVZAVMmN4bFRFM0NmYkktYjZAMbjFpMGtvOTVjQ3dyckdDQkE3bzVzRTNiMFNRVElwMVZAqdzc3LWF5TUdDNlp3eUZA5TDgzUjZAtalVMZAXpCcUs2em5RRlpTdAZDZD',
    ],
]);
?>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="footer-top d-flex flex-row justify-content-between align-items-center">
                        <div class="footer-logo wow fadeInUp">
                            <img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/images/hcca-logol.png">
                        </div>
                        <div class="social-area wow fadeInUp" data-wow-delay="300ms">
                            <a href=""><i class="fab fa-facebook-f"></i></a>
                            <a href=""><i class="fab fa-instagram"></i></a>
                            <a href=""><i class="fab fa-twitter"></i></a>
                            <a href=""><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="footer-details d-flex flex-md-row justify-content-between flex-column">
                        <div class="about-footer wow fadeInUp">
                            <h4>About Us</h4>
                            <?= $footerData->short_description;?>
                            <div class="col-12">
                                <a href="">
                                    <div class="goto-more"><span>View All Tours <i class="fas fa-arrow-right"></i></span></div>
                                </a>
                            </div>
                        </div>
                        <div class="link-footer wow fadeInUp" data-wow-delay="300ms">
                            <h4>About Us</h4>
                            <ul>
                                
                                <li><a href="<?php echo Yii::$app->request->baseUrl; ?>">Home</a></li>
                                <li><a href="<?php echo Yii::$app->request->baseUrl; ?>/packages">Packages</a></li>
                                <li><a href="<?php echo Yii::$app->request->baseUrl; ?>/visa">Visa</a></li>
                                <li><a href="<?php echo Yii::$app->request->baseUrl; ?>/accomodation">Accommodation</a></li>
                                <li><a href="<?php echo Yii::$app->request->baseUrl; ?>/rent-car">Rent a car</a></li>
                                <li><a href="<?php echo Yii::$app->request->baseUrl; ?>/about-us">About us</a></li>
                                <li><a href="<?php echo Yii::$app->request->baseUrl; ?>/contact-us">Contact us</a></li>
                                <?php
                                if (!Yii::$app->user->isGuest) { ?>
                                    <li>
                                        <a href="<?php echo Yii::$app->request->baseUrl; ?>/dashboard">Dashboard </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo Yii::$app->request->baseUrl; ?>/log-out">Logout</a>
                                    </li>

                                <?php    } else { ?>
                                    <li>
                                        <a href="" data-bs-toggle="modal" data-bs-target="#LoginEnquiry"><img src="<?php echo Yii::$app->request->baseUrl; ?>/images/user.png" alt="HCCA User"></a>
                                    </li>
                                <?php    }
                                ?>

                            </ul>
                        </div>
                        <div class="reach-footer wow fadeInUp" data-wow-delay="600ms">
                            <h4>Reach Us</h4>
                            <p><a href=""><?= $config->address;?></a></p>
                            <p><a href=""><?= $config->email;?></a></p>
                            <p><a href=""><?= $config->phone_number; ?></a></p>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="copy d-flex flex-md-row justify-content-between flex-column align-items-center">
                        <p class="wow fadeInUp">
                            <i class="far fa-copyright"></i> <?= date('Y');?> <a href="<?php echo Yii::$app->request->baseUrl; ?>">HCCA Tours</a> , All Rights Reserved
                        </p>
                        <div class="we-accept wow fadeInUp" data-wow-delay="300ms">
                            We Accept <img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/images/we-accept.png">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <?php
    $resetmodel = new PasswordResetRequestForm();

    ?>

    <div class="modal fade login-enquire-form" id="resetPassword" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">LOGIN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="reesetError hashing"></p>
                    <?php $form = ActiveForm::begin([
                        'id' => 'reset-form',
                        'action' => Yii::$app->request->baseUrl . '/user/forgot-password',
                        // 'enableAjaxValidation' => true,
                        'enableClientValidation' => true,
                        // 'validationUrl' => 'validation-rul',
                        'options' => ['enctype' => 'multipart/form-data', 'enableClientScript' => false]
                    ]); ?>
                    <div class="form-group">
                        <?= $form->field($resetmodel, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email', 'class' => 'form-control'])->label(false) ?>
                    </div>

                    <?= Html::submitButton('Submit', ['class' => 'my-btn log_button']) ?>
                    <?php ActiveForm::end(); ?>
                    <p>New to HCCA? <a href="" data-bs-dismiss="modal" aria-label="Close" data-bs-toggle="modal" data-bs-target="#CreateAccount">Create an account</a></p>
                </div>
            </div>
        </div>
    </div>
    <?php
    $logmodel = new LoginForm();

    ?>

    <div class="modal fade login-enquire-form" id="LoginEnquiry" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">LOGINN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="loginError"></p>
                    <?php $form = ActiveForm::begin([
                        'id' => 'log-form',
                        'action' => Yii::$app->request->baseUrl . '/user/login',
                        // 'enableAjaxValidation' => true,
                        'enableClientValidation' => true,
                        // 'validationUrl' => 'validation-rul',
                        'options' => ['enctype' => 'multipart/form-data', 'enableClientScript' => false]
                    ]); ?>
                    <div class="form-group">
                        <?= $form->field($logmodel, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email', 'class' => 'form-control'])->label(false) ?>
                    </div>
                    <div class="form-group reset-from ">
                        <?= $form->field($logmodel, 'password')->textInput(['maxlength' => true, 'placeholder' => 'Password', 'class' => 'form-control'])->label(false) ?>
                        <a class="reset" data-bs-dismiss="modal" aria-label="Close" data-bs-toggle="modal" data-bs-target="#resetPassword" href="">Reset Password</a>
                    </div>
                    <?= Html::submitButton('Submit', ['class' => 'my-btn log_button']) ?>
                    <?php ActiveForm::end(); ?>
                    <p>New to HCCA? <a href="" data-bs-dismiss="modal" aria-label="Close" data-bs-toggle="modal" data-bs-target="#CreateAccount">Create an account</a></p>
                </div>
            </div>
        </div>
    </div>
    <?php $regmodel = new User();

    $regmodel->scenario = "create_user" ?>
    <!-- Modal -->
    <div class="modal fade login-enquire-form" id="CreateAccount" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create an account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php $form = ActiveForm::begin([
                        'id' => 'reg-form',
                        'action' => Yii::$app->request->baseUrl . '/user/register',
                        // 'enableAjaxValidation' => true,
                        'enableClientValidation' => true,
                        // 'validationUrl' => 'validation-rul',
                        'options' => ['enctype' => 'multipart/form-data', 'enableClientScript' => false]
                    ]); ?>


                    <div class="form-group">
                        <?= $form->field($regmodel, 'first_name')->textInput(['maxlength' => true, 'placeholder' => 'Full Name', 'class' => 'form-control'])->label(false) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($regmodel, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email Address', 'class' => 'form-control'])->label(false) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($regmodel, 'mobile_number')->textInput(['maxlength' => true, 'placeholder' => 'Contact Number',  'class' => 'form-control'])->label(false) ?>

                    </div>
                    <div class="form-group ">

                        <?= $form->field($regmodel, 'password')->textInput(['maxlength' => true, 'placeholder' => 'Password', 'class' => 'form-control'])->label(false) ?>
                    </div>
                    <div class="form-group ">
                        <?= $form->field($regmodel, 'retype_password')->textInput(['maxlength' => true, 'placeholder' => 'Retype Password', 'class' => 'form-control'])->label(false) ?>

                    </div>
                    <span class="password_criteria">Password Must be contain atlease one lowercase,uppercase,digit and special charector</span>
                    <?= Html::submitButton('Create account', ['class' => 'my-btn reg_button']) ?>

                    <?php ActiveForm::end(); ?>
                    <p>Existing User? <a href="" data-bs-dismiss="modal" aria-label="Close" data-bs-toggle="modal" data-bs-target="#LoginEnquiry">Log in</a></p>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade login-enquire-form" id="accountSuccess" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Account Created</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body succes_body">

                    <p>Please Verify your email by clicking the verification link sent to your email. Please check the junk folder as well</p>
                </div>
            </div>
        </div>
    </div>
    <div class="fixed-chat">
        <div class="whatsapp d-flex justify-content-center align-items-center">
            <a href=""><img src="<?php echo Yii::$app->request->baseUrl; ?>/images/whatsapp.png"></a>
        </div>
        <div class="whatsapp message d-flex justify-content-center align-items-center">
            <a href=""><img src="<?php echo Yii::$app->request->baseUrl; ?>/images/message.png"></a>
        </div>
    </div>


    <a href="#" onclick="goToByScroll('home-u')"> <i class="fas fa-arrow-up go-top"></i> </a>
<!-- <p>asasa</p> -->
    <?php $this->endBody() ?>
    
    <?php
    $this->registerJs(
        <<< EOT_JS_CODE


        function goToByScroll(id){
		          $('html,body').animate({scrollTop: $("#"+id).offset().top-0},'slow');
		    }
            new WOW().init();
            
EOT_JS_CODE
    );
    ?>
</body>

</html>
<?php $this->endPage() ?>