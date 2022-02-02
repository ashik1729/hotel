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
                    <?= Html::encode('Products Services') ?>

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
                        'title',
                        // 'sku',
                        [
                            'attribute' => 'type',
                            'value' => function ($data) {
                                if ($data->status == 1) {
                                    return "Products";
                                } else if ($data->status == 2) {

                                    return "Shop Service";
                                } else if ($data->status == 3) {
                                    return "Home Service";
                                }
                            },
//                                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'category_id',
                            'header' => 'Category',
                            'value' => function($model) {
                                if ($model->category->parent != $model->category->id) {

                                    $html = '<nav aria-label = "breadcrumb"> <ol class = "breadcrumb">';
                                    $catmodel = \common\models\Category::find()->where(['id' => $model->category_id])->one();

                                    $option_items = Yii::$app->SelectCategory->selectCategories($catmodel);
                                    $option_data = explode('-', $option_items);
                                    $option_data_array = array_reverse($option_data);
                                    if ($option_data_array != NULL) {
                                        $i = 1;
                                        $count = count($option_data_array);
                                        foreach ($option_data_array as $option_data_arr) {
                                            $option_cat = \common\models\Category::find()->where(['id' => $option_data_arr])->one();
                                            $caturl = Yii::$app->request->baseUrl . '/category/view?id=' . $option_data_arr;

                                            if ($option_cat != NULL) {
                                                if ($i == $count) {
                                                    $html .= '<li class="breadcrumb-item active" aria-current="page"><a href = "' . $caturl . '">' . $option_cat->category_name . '</a></li>';
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
                                    $caturl = Yii::$app->request->baseUrl . '/category/view?id=' . $model->category->id;

                                    return '<a href = "' . $caturl . '">' . $model->category->category_name . '</a>';
                                }
//                                            return "<span style=' text-transform: capitalize'>" . $model->franchise->first_name . ' ' . $model->franchise->last_name . '(' . $gfranchise->country0->country_name . ')' . "</span>";
                            },
                            'format' => 'html',
                        ],
                    
                        'canonical_name',
                        [
                            'attribute' => 'image',
                            'value' => function ($data) {
                                if ($data->image != '') {
                                    $imgPath = Yii::$app->ManageRequest->getImage($data);
                                } else {
                                    $imgPath = Yii::$app->request->baseUrl . '/img/no-image.jpg';
                                }
                                return $imgPath;
                            },
                            'format' => ['image', ['width' => '100', 'height' => '100']],
                        ],
                        [
                            'attribute' => 'gallery',
                            'value' => function ($data) {
                                if ($data->gallery != '') {
                                    $images = explode(',', $data->gallery);
                                    $result_html = '';
                                    if ($images != NULL) {
                                        foreach ($images as $image) {
                                            $imgPath = Yii::$app->request->baseUrl . '/../uploads/products/' . base64_encode($data->id) . '/gallery/' . $image;
                                            $result_html .= '<div class ="img_gallery">                    <a href="' . $imgPath . '" class="thumbnail"><img src="' . $imgPath . '" alt="Image Alt" /></a></div>';
//                                            $result_html .= "<img class='thumb_image' src='" . Yii::$app->request->baseUrl . '/../uploads/products/' . base64_encode($data->sku) . '/gallery/' . $image . "' />";
                                        }
                                    }
                                    return $result_html;
                                } else {

                                    return "<img src='" . Yii::$app->request->baseUrl . '/img/no-image.jpg' . "' />";
                                }
                            },
                            'format' => 'html'
                        ],
                        'sort_order',
                        'price',
                        [
                            'attribute' => 'discount_type',
                            'value' => function ($data) {
                                if ($data->discount_rate == 1) {
                                    return "Flat";
                                } else if ($data->discount_rate == 2) {

                                    return "Percentage";
                                } else {
                                    return "Not Set";
                                }
                            },
//                                            'format' => 'html',
                        ],
                        'discount_rate',
                   
                        'new_from',
                        'new_to',
                        'sale_from',
                        'sale_to',
                        'discount_from',
                        'discount_to',
                        'search_tag:ntext',

                        [
                            'attribute' => 'is_featured',
                            'value' => function ($data) {
                                if ($data->is_featured == 1) {
                                    return "Yes";
                                } else {
                                    return "No";
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
                        'meta_title',
                        'meta_description:ntext',
                        'meta_keywords:ntext',
                        'tax_applicable',
                        'tax_amount',
                        'min_quantity',
                        'quantity',
                        'weight_class',
                        'weight',
                        'short_description_en:ntext',
                        'long_description_en:ntext',
                    ],
                ])
                ?>


            </div>
        </div>
    </div>
</div>
