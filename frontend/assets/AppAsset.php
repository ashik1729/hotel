<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/animate.min.css',
        'css/bootstrap@5.0.2/css/bootstrap.min.css',
        'css/owlcarousel/css/owl.carousel.min.css',
        'css/owlcarousel/css/owl.theme.default.min.css',
        'css/style.css',
        'css/responsive.css',
    ];
    public $js = [
        'https://kit.fontawesome.com/df7cb1b087.js',
        'js/counter/ajax-jquery.min.js',
        'js/counter/counter-waypoints.min.js',
        'js/counter/js-counter.js',
        'css/owlcarousel/js/owl.carousel.js',
        'js/core.js',
        'js/wow.min.js',
        'js/jquery.fittext.js',
        'css/bootstrap@5.0.2/js/bootstrap.bundle.min.js', 
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}