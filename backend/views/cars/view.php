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
                    <?= Html::encode('Cars') ?>

                    <div class="pull-right">
                        <?= Html::a(Html::tag('b', 'keyboard_arrow_left', ['class' => 'material-icons']), ['index'], [
                            'class' => 'btn btn-xs btn-success btn-round btn-fab',
                            'rel' => "tooltip",
                            'data' => [
                                'placement' => 'bottom',
                                'original-title' => 'Back'
                            ],
                        ])
                        ?>
                        <?= Html::a(Html::tag('b', 'create', ['class' => 'material-icons']), ['update', 'id' => $model->id], [
                            'class' => 'btn btn-xs btn-success btn-round btn-fab',
                            'rel' => "tooltip",
                            'data' => [
                                'placement' => 'bottom',
                                'original-title' => 'Edit User'
                            ],
                        ])
                        ?>
                        <?= Html::a(Html::tag('b', 'delete', ['class' => 'material-icons']), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-xs btn-danger btn-round btn-fab',
                            'rel' => "tooltip",
                            'onclick' => "return confirm('Are you sure you want to delete this item?')",
                            'data' => [
                                'confirm' => \Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                                'placement' => 'bottom',
                                'original-title' => 'Delete User'
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
                        'title:ntext',
                        [
                            'attribute' => 'brand',
                            'label' => 'Brands',
                          
                            'value' => function($model) {
                                return $model->brand0->title;
                            },
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'type_of_car',
                            'label' => 'Type Pf Car',
                        
                            'value' => function($model) {
                                return $model->typeOfCar->title;
                            },
                            'format' => 'html',
                        ],
                        'long_description:ntext',
                        'short_description:ntext',
                        [
                            'attribute' => 'status',
                            'header' => 'Status',
                            'filterInputOptions' => ['class' => 'form-control ', 'id' => null, 'prompt' => 'All', 'data-live-search' => "true", 'title' => "Select a Status", 'data-hide-disabled' => "true"], // to change 'Todos' instead of the blank option
                            'value' => function ($data) {
                                if ($data->status == 1) {
                                    return "<span style='color:green;font-weight:bold'>Enable</span>";
                                } else {
                                    return "<span style='color:red;font-weight:bold'>Disable</span>";
                                }
                            },
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'image',
                            'value' => function ($data) {
                                if ($data->image != '') {
                                    return Yii::$app->request->baseUrl . '/../uploads/cars/' . $data->id . '/image/' . $data->image;
                                } else {
                                    return Yii::$app->request->baseUrl . '/img/no-image.jpg';
                                }
                            },
                            'format' => 'image'
                        ],
                        [
                            'attribute' => 'gallery',
                            'value' => function ($data) {
                                if ($data->gallery != '') {
                                    $images = explode(',', $data->gallery);
                                    $result_html = '';
                                    if ($images != NULL) {
                                        foreach ($images as $image) {
                                            $result_html .= "<img src='" . Yii::$app->request->baseUrl . '/../uploads/cars/' . $data->id . '/gallery/' . $image . "' />";
                                        }
                                    }
                                    return $result_html;
                                } else {

                                    return "<img src='" . Yii::$app->request->baseUrl . '/img/no-image.jpg' . "' />";
                                }
                            },
                            'format' => 'html'
                        ],
                        'model_year',
                        'series',
                        'day_price',
                        'day_offer',
                        'week_price',
                        'week_offer',
                        'month_price',
                        'month_offer',
                      
                        'sort_order',
                    ],
                ]) ?>


            </div>
        </div>
    </div>
</div>