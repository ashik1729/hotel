<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
/**
*
* @package    Material Dashboard Yii2
* @author     CodersEden <hello@coderseden.com>
    * @link       https://www.coderseden.com
    * @copyright  2020 Material Dashboard Yii2 (https://www.coderseden.com)
    * @license    MIT - https://www.coderseden.com
    * @since      1.0
    */
    use yii\helpers\Html;
    use yii\widgets\DetailView;
    ?>
    <div class="content">
        <div class="container-fluid">
            <div class="card ">
                <div class="card-header card-header-primary card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">account_box</i>
                    </div>
                    <h4 class="card-title">
                        <?= Html::encode('Packages Dates')?>

                        <div class="pull-right">
                            <?=  Html::a(Html::tag('b', 'keyboard_arrow_left', ['class' => 'material-icons']), ['index'], [
                                'class' => 'btn btn-xs btn-success btn-round btn-fab',
                                'rel' => "tooltip",
                                'data' => [
                                'placement' => 'bottom',
                                'original-title' => 'Back'
                                ],
                            ])
                            ?>
                        </div>
                    </h4>
                </div>
                <div class="card-body">

                    <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                                'id',
                    'package_id',
                    'package_date',
                    'package_quantity',
                    'created_at',
                    'updated_at',
                    ],
                    ]) ?>


                </div>
            </div>
        </div>
    </div>
