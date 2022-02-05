<?php

use yii\helpers\Html;
use backend\assets\AppAsset;

AppAsset::register($this);
$this->beginPage();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Extra details for Demo -->
        <link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?= \Yii::getAlias('@web/img/favicon/apple-touch-icon-57x57.png'); ?>" />
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?= \Yii::getAlias('@web/img/favicon/apple-touch-icon-114x114.png'); ?>" />
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?= \Yii::getAlias('@web/img/favicon/apple-touch-icon-72x72.png'); ?>" />
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?= \Yii::getAlias('@web/img/favicon/apple-touch-icon-144x144.png'); ?>" />
        <link rel="apple-touch-icon-precomposed" sizes="60x60" href="<?= \Yii::getAlias('@web/img/favicon/apple-touch-icon-60x60.png'); ?>" />
        <link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?= \Yii::getAlias('@web/img/favicon/apple-touch-icon-120x120.png'); ?>" />
        <link rel="apple-touch-icon-precomposed" sizes="76x76" href="<?= \Yii::getAlias('@web/img/favicon/apple-touch-icon-76x76.png'); ?>" />
        <link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?= \Yii::getAlias('@web/img/favicon/apple-touch-icon-152x152.png'); ?>" />
        <link rel="icon" type="image/png" href="<?= \Yii::getAlias('@web/img/favicon/favicon-196x196.png'); ?>" sizes="196x196" />
        <link rel="icon" type="image/png" href="<?= \Yii::getAlias('@web/img/favicon/favicon-96x96.png'); ?>" sizes="96x96" />
        <link rel="icon" type="image/png" href="<?= \Yii::getAlias('@web/img/favicon/favicon-32x32.png'); ?>" sizes="32x32" />
        <link rel="icon" type="image/png" href="<?= \Yii::getAlias('@web/img/favicon/favicon-16x16.png'); ?>" sizes="16x16" />
        <link rel="icon" type="image/png" href="<?= \Yii::getAlias('@web/img/favicon/favicon-128.png'); ?>" sizes="128x128" />
        <meta name="msapplication-TileColor" content="#FFFFFF" />
        <meta name="msapplication-TileImage" content="<?= \Yii::getAlias('@web/img/favicon/mstile-144x144.png'); ?>" />
        <meta name="msapplication-square70x70logo" content="<?= \Yii::getAlias('@web/img/favicon/mstile-70x70.png'); ?>" />
        <meta name="msapplication-square150x150logo" content="<?= \Yii::getAlias('@web/img/favicon/mstile-150x150.png'); ?>" />
        <meta name="msapplication-wide310x150logo" content="<?= \Yii::getAlias('@web/img/favicon/mstile-310x150.png'); ?>" />
        <meta name="msapplication-square310x310logo" content="<?= \Yii::getAlias('@web/img/favicon/mstile-310x310.png'); ?>" />
        <meta name="author" content="CodersEden.com" />
        <meta name="theme-color" content="#ffffff">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
        <!-- CSS Files -->

        <?= Html::csrfMetaTags() ?>
        <title>HCCA Admin</title>
        <?php $this->head() ?>

        <script type="text/javascript">
            var baseurl = "<?php print \yii\helpers\Url::base(); ?>";

            var basepath = "<?php print \yii\helpers\Url::base(); ?>";

            var slug = function (str) {
                var $slug = '';
                var trimmed = $.trim(str);
                $slug = trimmed.replace(/[^a-z0-9-]/gi, '-').
                        replace(/-+/g, '-').
                        replace(/^-|-$/g, '');
                return $slug.toLowerCase();
            };
        </script>
    </head>
    <body class="">
        <!-- Extra Body details for Demo -->
        <?php $this->beginBody() ?>
        <div class="loader-wrapp">
            <div class="loader">

            </div>
        </div>
        <div class="wrapper">
            <?php
            if (Yii::$app->user->identity->interface == 'merchant') {

                echo $this->render(
                        'left_merchant.php'
                );
            } else if (Yii::$app->user->identity->interface == 'franchise') {

                echo $this->render(
                        'left_franchise.php'
                );
            } else if (Yii::$app->user->identity->interface == 'admin') {

                echo $this->render(
                        'left.php'
                );
            }
            ?>
            <div class="main-panel">
                <?=
                $this->render(
                        'header.php'
                )
                ?>

                <?=
                $this->render(
                        'content.php', ['content' => $content]
                )
                ?>

                <?=
                $this->render(
                        'footer.php'
                )
                ?>
            </div>
        </div>

        <?php
//        $this->render(
//                'plugin.php'
//        )
        ?>

        <!--   Core JS Files   -->
        <script src="<?= \Yii::getAlias('@web/js/core/jquery.min.js'); ?>"></script>
        <?php $this->endBody() ?>



        <?php
        $this->registerJs(<<< EOT_JS_CODE


    $(document).ready(function () {
                // Javascript method's body can be found in assets/js/demos.js
//                md.initDashboardPageCharts();

            });

EOT_JS_CODE
        );
        ?>

      


    </body>
</html>
<?php $this->endPage() ?>
