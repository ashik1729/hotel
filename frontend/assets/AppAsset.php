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
        'css/site.css',
        'vendors/bootstrap/css/bootstrap.min.css',
        'fonts/font-awesome-4.7.0/css/font-awesome.min.css',
        'css/util.css',
        'css/main.css',
    ];
    public $js = [
        'vendors/jquery/jquery-3.2.1.min.js',
        'vendors/bootstrap/js/popper.js',
        'vendors/bootstrap/js/bootstrap.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
