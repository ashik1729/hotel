<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

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
        <a class="mobile-logo-area" href="index.html"><img class="mobile-nav-logo" src="<?php echo Yii::$app->request->baseUrl; ?>/images/hcca-logol.png" alt="HCCA Group Logo"></a>
        <button class="btn btn-menu-close">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
        <ul class="mobile-menu d-flex align-items-center justify-content-center flex-column">
            <li class="menu-item"><a href="home.html">Packages</a></li>
            <li class="menu-item"><a href="home.html">Visa</a></li>
            <li class="menu-item"><a href="home.html">Accommodation</a></li>
            <li class="menu-item"><a href="">Rent a car</a></li>
            <li class="menu-item"><a href="home.html">About us</a></li>
            <li class="menu-item"><a href="home.html">Contact us</a></li>
            <li class="menu-item"><a href="" data-bs-toggle="modal" data-bs-target="#LoginEnquiry"><img src="<?php echo Yii::$app->request->baseUrl; ?>/images/user.png" alt="HCCA User"></a></li>
        </ul>
    </nav>

    <header id="home-u">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.html"><img src="<?php echo Yii::$app->request->baseUrl; ?>/images/hcca-logol.png" alt="HCCA Group Logo"></a>
                <button class="navbar-toggler btn-menu-open" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon">
                        <img src="<?php echo Yii::$app->request->baseUrl; ?>/images/stroke-miterlimit.svg">
                    </span>
                </button>
                <div class="collapse navbar-collapse menubar" id="navbarSupportedContent">
                    <ul class="navbar-nav ">
                        <li class="nav-item">
                            <a class="nav-link" href="home.html">Packages</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="home.html"> Visa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="home.html">Accommodation</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="home.html">Rent a car</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="home.html">About us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="home.html">Contact us</a>
                        </li>
                        <li class="nav-item nav-last">
                            <a class="nav-link" href="" data-bs-toggle="modal" data-bs-target="#LoginEnquiry"><img src="<?php echo Yii::$app->request->baseUrl; ?>/images/user.png" alt="HCCA User"></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <?= $content ?>
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
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                consequat.
                            </p>
                            <div class="col-12">
                                <a href="">
                                    <div class="goto-more"><span>View All Tours <i class="fas fa-arrow-right"></i></span></div>
                                </a>
                            </div>
                        </div>
                        <div class="link-footer wow fadeInUp" data-wow-delay="300ms">
                            <h4>About Us</h4>
                            <ul>
                                <li><a href="home.html">Home</a></li>
                                <li><a href="home.html">Packages</a></li>
                                <li><a href="home.html">Visa</a></li>
                                <li><a href="home.html">Accommodation</a></li>
                                <li><a href="home.html">Rent A Car </a></li>
                                <li><a href="home.html">About Us</a></li>
                                <li><a href="home.html">Contact Us</a></li>
                                <li><a href="home.html">Login/Register </a></li>
                            </ul>
                        </div>
                        <div class="reach-footer wow fadeInUp" data-wow-delay="600ms">
                            <h4>Reach Us</h4>
                            <p><a href="">932 Demo address here UAE, 33060</a></p>
                            <p><a href="">example@example.com</a></p>
                            <p><a href="">(123) 1234 567890</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="copy d-flex flex-md-row justify-content-between flex-column align-items-center">
                        <p class="wow fadeInUp">
                            <i class="far fa-copyright"></i> 2021 <a href="">HCCA Tours</a> , All Rights Reserved
                        </p>
                        <div class="we-accept wow fadeInUp" data-wow-delay="300ms">
                            We Accept <img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/images/we-accept.png">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <div class="fixed-chat">
        <div class="whatsapp d-flex justify-content-center align-items-center">
            <a href=""><img src="<?php echo Yii::$app->request->baseUrl; ?>/images/whatsapp.png"></a>
        </div>
        <div class="whatsapp message d-flex justify-content-center align-items-center">
            <a href=""><img src="<?php echo Yii::$app->request->baseUrl; ?>/images/message.png"></a>
        </div>
    </div>


    <a href="#" onclick="goToByScroll('home-u')"> <i class="fas fa-arrow-up go-top"></i> </a>

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