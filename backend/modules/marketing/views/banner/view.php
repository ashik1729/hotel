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
        <?php if (Yii::$app->session->hasFlash("success")): ?>

            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="material-icons">close</i>
                </button>
                <span>
                    <?= Yii::$app->session->getFlash("success") ?>
                </span>
            </div>
        <?php endif; ?>

        <?php if (Yii::$app->session->hasFlash("error")): ?>

            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="material-icons">close</i>
                </button>
                <span>                <?= Yii::$app->session->getFlash("error") ?>
                </span>
            </div>
        <?php endif; ?>
        <div class="card ">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">account_box</i>
                </div>
                <h4 class="card-title">
                    <?= Html::encode('Banners') ?>

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
                        'name',
                        [
                            'attribute' => 'store',
                            'value' => function ($data) {
                                return $data->store0->first_name . ' ' . $data->store0->last_name . ' (' . $data->store0->email . ')';
                            },
                        ],
                        'promotion_from',
                        'promotion_to',
                        [
                            'attribute' => 'banner_type',
                            'value' => function ($data) {
                                if ($data->banner_type == 0) {
                                    return "Free";
                                } else if ($data->banner_type == 1) {

                                    return "Paid";
                                } else {
                                    return "Not Described";
                                }
                            },
//                                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'file_type',
                            'value' => function ($data) {
                                if ($data->file_type == 1) {
                                    return "Image";
                                } else if ($data->file_type == 1) {

                                    return "File";
                                } else {
                                    return "Not Described";
                                }
                            },
//                                            'format' => 'html',
                        ],
//                        'file',
                        [
                            'attribute' => 'file_and',
                            'value' => function ($data) {
                                if ($data->file_and != '') {
                                    if ($data->file_type == 1) {
                                        return '<img src="' . Yii::$app->request->baseUrl . '/../uploads/marketing_banners/' . $data->id . '/android/' . $data->file_and . '"/>';
                                    } else if ($data->file_type == 2) {
                                        return '<a target="new" href="' . Yii::$app->request->baseUrl . '/../uploads/marketing_banner/' . $data->id . '/android/' . $data->file_and . '">View File</a>';
                                    } else {
                                        return " No File To Display";
                                    }
                                } else {
                                    return " No File To Display";
                                }
                            },
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'file_ios',
                            'value' => function ($data) {
                                if ($data->file_ios != '') {
                                    if ($data->file_type == 1) {
                                        return '<img src="' . Yii::$app->request->baseUrl . '/../uploads/marketing_banners/' . $data->id . '/ios/' . $data->file_ios . '"/>';
                                    } else if ($data->file_type == 2) {
                                        return '<a target="new" href="' . Yii::$app->request->baseUrl . '/../uploads/marketing_banner/' . $data->id . '/ios/' . $data->file_ios . '">View File</a>';
                                    } else {
                                        return " No File To Display";
                                    }
                                } else {
                                    return " No File To Display";
                                }
                            },
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'status',
                            'value' => function ($data) {
                                if ($data->status == 0) {
                                    return "Disable";
                                } else if ($data->status == 1) {

                                    return "Enable";
                                } else {
                                    return "Not Described";
                                }
                            },
//                                            'format' => 'html',
                        ],
                        'created_at',
                        'updated_at',
                        [
                            'attribute' => 'created_by',
                            'value' => function ($data) {
                                $get_admin = common\models\UserAdmin::find()->where(['id' => $data->created_by])->one();
                                if ($get_admin != NULL) {
                                    return $get_admin->first_name . ' ' . $get_admin->last_name;
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

                                    return $get_admin->first_name . ' ' . $get_admin->last_name;
                                } else {
                                    return '';
                                }
                            },
                        ],
                        'sort_order',
                        [
                            'attribute' => 'map_type',
                            'value' => function ($data) {
                                if ($data->map_type == 0) {
                                    return "No Mapping";
                                } else if ($data->map_type == 1) {
                                    return "Products";
                                } else if ($data->map_type == 2) {
                                    return "Category";
                                } else {
                                    return "Other";
                                }
                            },
//                                            'format' => 'html',
                        ],
                        'map_to',
                    ],
                ])
                ?>


            </div>
        </div>
    </div>
</div>
