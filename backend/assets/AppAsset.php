<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
//https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-datetimepicker/2.7.1/css/bootstrap-material-datetimepicker.min.css">
//https://fonts.googleapis.com/icon?family=Material+Icons">
//https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
//https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
//https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-datetimepicker/2.7.1/js/bootstrap-material-datetimepicker.min.js"></script>-->
//https://cdnjs.com/libraries/moment.js-->
//https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/locale/ja.js"></script>

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/material-dashboard.css?v=2.1.2',
        'css/bootstrap-gallery.css',
        'css/demo.css',
        'css/main.css',
        'https://unpkg.com/@fullcalendar/core@4.3.1/main.min.css',
        'https://unpkg.com/@fullcalendar/daygrid@4.3.0/main.min.css',
        'https://unpkg.com/@fullcalendar/timegrid@4.3.0/main.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css'
    ];
    public $js = [
        'js/core/popper.min.js',
        'js/core/bootstrap-material-design.min.js',
        'js/plugins/perfect-scrollbar.jquery.min.js',
        'js/plugins/moment.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-datetimepicker/2.7.1/js/bootstrap-material-datetimepicker.min.js',
        'js/plugins/sweetalert2.js',
        'js/plugins/jquery.validate.min.js',
        'js/plugins/jquery.bootstrap-wizard.js',
        'js/plugins/bootstrap-selectpicker.js',
        'js/plugins/bootstrap-datetimepicker.min.js',
        'js/plugins/jquery.dataTables.min.js',
        'js/plugins/bootstrap-tagsinput.js',
//        'js/plugins/jasny-bootstrap.min.js',
        'js/plugins/fullcalendar.min.js',
        'js/plugins/jquery-jvectormap.js',
        'js/plugins/nouislider.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js',
        'js/plugins/arrive.min.js',
        'js/plugins/chartist.min.js',
        'js/plugins/bootstrap-notify.js',
        'js/material-dashboard.js?v=2.1.2',
        'css/demo.js',
        'js/main.js',
        'js/bootstrap-gallery.js',
        'js/plugin.js',
        'https://maps.google.com/maps/api/js?libraries=places&key=AIzaSyBUbq008DcEr8BmXl-oj_X590Hvw-ETAqs',
        'js/locationpicker.jquery.js',
        'https://unpkg.com/@fullcalendar/core@4.3.1/main.min.js',
        'https://unpkg.com/@fullcalendar/interaction@4.3.0/main.min.js',
        'https://unpkg.com/@fullcalendar/daygrid@4.3.0/main.min.js',
        'https://unpkg.com/@fullcalendar/timegrid@4.3.0/main.min.js',
        'js/merchant.js',
        'js/common.js',
        'js/developer.js',
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
