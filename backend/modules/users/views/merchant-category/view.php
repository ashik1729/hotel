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
                    <?= Html::encode('Merchant Categories') ?>

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
                        <a class="btn btn-xs btn-primary btn-round btn-fab" href="<?= \yii\helpers\Url::to(['create']); ?>" rel="tooltip" data-placement="bottom" data-original-title="Add <?= Html::encode($this->title) ?>"><b class="material-icons">add</b></a>

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
                        'name_ar',
                        'description:ntext',
                        'description_ar:ntext',
                        [
                            'attribute' => 'image',
                            'value' => function ($data) {
                                if ($data->image != '') {
                                    return Yii::$app->request->baseUrl . '/../uploads/merchant-category/' . $data->image;
                                } else {
                                    return Yii::$app->request->baseUrl . '/img/no-image.jpg';
                                }
                            },
                            'format' => 'image'
                        ],
                        [
                            'attribute' => 'status',
                            'value' => function ($data) {
                                if ($data->status != '') {
                                    if ($data->status = 1) {
                                        return "Enable";
                                    } else {
                                        return "Disable";
                                    }
                                } else {
                                    return "Not Found";
                                }
                            },
                            'format' => 'raw'
                        ],
                        'sort_order',
                        'created_at',
                        [
                            'attribute' => 'created_by',
                            'value' => function ($data) {
                                $get_admin = common\models\UserAdmin::find()->where(['id' => $data->created_by])->one();
                                if ($get_admin != NULL) {
                                    $type = $data->created_by_type == 2 ? 'Admin' : ($data->created_by_type == 1 ? 'User' : ($data->created_by_type == 3 ? 'Merchant' : 'Franchise'));
                                    return $get_admin->first_name . ' ' . $get_admin->last_name . '(' . $type . ')';
                                } else {
                                    return '';
                                }
                            },
                        ],
                        'updated_at',
                        [
                            'attribute' => 'updated_by',
                            'value' => function ($data) {
                                $get_admin = common\models\UserAdmin::find()->where(['id' => $data->updated_by])->one();
                                if ($get_admin != NULL) {
                                    $type = $data->updated_by_type == 2 ? 'Admin' : 'user';
                                    return $get_admin->first_name . ' ' . $get_admin->last_name . '(' . $type . ')';
                                } else {
                                    return '';
                                }
                            },
                        ],
                    ],
                ])
                ?>


            </div>
        </div>
    </div>
</div>
