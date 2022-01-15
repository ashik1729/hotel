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
                    <?= Html::encode('Events') ?>

                    <div class="pull-right">
                        <?=
                        Html::a(Html::tag('b', 'keyboard_arrow_left', ['class' => 'material-icons']), ['index'], [
                            'class' => 'btn btn-xs btn-success btn-round btn-fab',
                            'rel' => "tooltip",
                            'data' => [
                                'placement' => 'bottom',
                                'original-title' => 'Back'
                            ],
                        ])
                        ?>
                        <?=
                        Html::a(Html::tag('b', 'create', ['class' => 'material-icons']), ['update', 'id' => $model->id], [
                            'class' => 'btn btn-xs btn-success btn-round btn-fab',
                            'rel' => "tooltip",
                            'data' => [
                                'placement' => 'bottom',
                                'original-title' => 'Edit User'
                            ],
                        ])
                        ?>
                        <?=
                        Html::a(Html::tag('b', 'delete', ['class' => 'material-icons']), ['delete', 'id' => $model->id], [
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

                <?=
                DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'title_en',
                        'title_ar',
                        'description_en:ntext',
                        'description_ar:ntext',
                        [
                            'attribute' => 'date_time',
                            'value' => function ($data) {
                                if ($data->date_time != '') {
                                    return date("Y-M-d h:i A", strtotime($data->date_time));
                                }
                            },
                            'format' => 'html'
                        ],
                        [
                            'attribute' => 'country',
                            'value' => function ($data) {
                                return $data->country0->country_name;
                            },
                        ],
                        [
                            'attribute' => 'city',
                            'value' => function ($data) {
                                return $data->city0->name_en;
                            },
                        ],
                        [
                            'attribute' => 'file',
                            'value' => function ($data) {
                                if ($data->file != '') {
                                    return '<a href="' . Yii::$app->request->baseUrl . '/../uploads/events/' . $data->id . '/file/' . $data->file . '" target="new">View File</a>';
                                } else {
                                    return Yii::$app->request->baseUrl . '/img/no-image.jpg';
                                }
                            },
                            'format' => 'html'
                        ],
                        [
                            'attribute' => 'gallery',
                            'value' => function ($data) {
                                if ($data->gallery != '') {
                                    $images = explode(',', $data->gallery);
                                    $result_html = '';
                                    if ($images != NULL) {
                                        foreach ($images as $image) {
                                            $result_html .= "<img src='" . Yii::$app->request->baseUrl . '/../uploads/events/' . $data->id . '/gallery/' . $image . "' />";
                                        }
                                    }
                                    return $result_html;
                                } else {

                                    return "<img src='" . Yii::$app->request->baseUrl . '/img/no-image.jpg' . "' />";
                                }
                            },
                            'format' => 'html'
                        ],
                        [
                            'attribute' => 'status',
                            'value' => function ($data) {
                                if ($data->status == 1) {
                                    return "Yes";
                                } else {
                                    return "No";
                                }
                            },
//                                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'created_by',
                            'value' => function ($data) {
                                $get_admin = common\models\UserAdmin::find()->where(['id' => $data->created_by])->one();
                                if ($get_admin != NULL) {
                                    $type = $get_admin->roles->role_name;
                                    return $get_admin->first_name . ' ' . $get_admin->last_name . '(' . $type . ')';
                                } else {
                                    return '';
                                }
                            },
                        ],
                        [
                            'attribute' => 'updated_by',
                            'value' => function ($data) {
                                $get_admin = common\models\UserAdmin::find()->where(['id' => $data->updated_by])->one();
                                if ($get_admin != NULL) {
                                    $type = $get_admin->roles->role_name;

                                    return $get_admin->first_name . ' ' . $get_admin->last_name . '(' . $type . ')';
                                } else {
                                    return '';
                                }
                            },
                        ],
                        'created_at',
                        'updated_at',
                        'sort_order',
                    ],
                ])
                ?>


            </div>
        </div>
    </div>
</div>
