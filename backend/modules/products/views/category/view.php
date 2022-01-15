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
                    <?= Html::encode('Categories') ?>

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
                        [
                            'attribute' => 'parent',
                            'header' => 'Parent Category',
                            'value' => function ($data) {
                                if ($data->parent != $data->id) {
                                    $html = '<nav aria-label = "breadcrumb"> <ol class = "breadcrumb">';
                                    $option_items = Yii::$app->SelectCategory->selectCategories($data);
                                    $option_data = explode('-', $option_items);
                                    $option_data_array = array_reverse($option_data);
                                    if ($option_data_array != NULL) {
                                        $i = 1;
                                        $count = count($option_data_array);
                                        foreach ($option_data_array as $option_data_arr) {
                                            $option_cat = \common\models\Category::find()->where(['id' => $option_data_arr])->one();
                                            $caturl = 'view?id=' . $option_data_arr;
                                            if ($option_cat != NULL) {
                                                if ($i == $count) {
//                                                    $html .= '<li class="breadcrumb-item active" aria-current="page">' . $option_cat->category_name . '</li>';
                                                } else {
                                                    $html .= '<li class = "breadcrumb-item"><a href = "' . $caturl . '">' . $option_cat->category_name . '</a></li>';
                                                }
                                                $i++;
                                            }
                                        }
                                    }

                                    $html .= '</ol></nav>';
                                    return $html;
                                } else {
                                    return 'First Leval Category';
                                }
                            },
                            'format' => 'html'
                        ],
                        'category_name',
                        [
                            'attribute' => 'description',
                            'value' => function ($data) {
                                return $data->description;
                            },
                            'format' => 'html'
                        ],
                        [
                            'attribute' => 'image',
                            'value' => function ($data) {
                                if ($data->image != '') {
                                    return Yii::$app->request->baseUrl . '/../uploads/category/' . $data->id . '/image/' . $data->image;
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
                                            $result_html .= "<img src='" . Yii::$app->request->baseUrl . '/../uploads/category/' . $data->id . '/gallery/' . $image . "' />";
                                        }
                                    }
                                    return $result_html;
                                } else {

                                    return "<img src='" . Yii::$app->request->baseUrl . '/img/no-image.jpg' . "' />";
                                }
                            },
                            'format' => 'html'
                        ],
                        'search_tag:ntext',
                        'sort_order',
                        [
                            'attribute' => 'header_visibility',
                            'value' => function ($data) {
                                if ($data->header_visibility == 1) {
                                    return "Yes";
                                } else {
                                    return "No";
                                }
                            },
//                                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'status',
                            'value' => function ($data) {
                                if ($data->status == 1) {
                                    return "Enable";
                                } else {
                                    return "Disable";
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
                        'meta_title',
                        'meta_description:ntext',
                        'meta_keywords:ntext',
                    ],
                ])
                ?>


            </div>
        </div>
    </div>
</div>
