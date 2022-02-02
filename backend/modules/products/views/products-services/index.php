<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ProductsServicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Packages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content products-services-index">
    <?php $urll = str_replace(" ", "-", strtolower(Html::encode($this->title))) ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">account_box</i>
                        </div>
                        <h4 class="card-title">

                            <?= Html::encode($this->title) ?>
                            <div class="pull-right">


                                <a class="btn btn-xs btn-primary btn-round btn-fab" href="<?= \yii\helpers\Url::to(['create']); ?>" rel="tooltip" data-placement="bottom" data-original-title="Add <?= Html::encode($this->title) ?>"><b class="material-icons">add</b></a>
                            </div>
                        </h4>
                    </div>
                    <?php
                    $datas = \common\models\Category::find()->all();
                    $options = array();
                    if ($datas != NULL) {
                        foreach ($datas as $data) {

                            if (!empty($data)) {
                                $option_items = Yii::$app->SelectCategory->selectCategories($data);
                                $option_data = explode('-', $option_items);
                                $option_data_array = array_reverse($option_data);
                                $latest_option = [];
                                if ($option_data_array != NULL) {
                                    foreach ($option_data_array as $option_data_arr) {
                                        $option_cat = \common\models\Category::find()->where(['id' => $option_data_arr])->one();
                                        $latest_option[] = $option_cat->category_name;
                                    }
                                }

                                $option_text = implode(' -> ', $latest_option);

                                $options[$data->id] = $option_text;
                            }
                        }
                    }
                    ?>
                    <div class="card-body">
                        <div class="material-datatables">
                          
                    
                            <?=
                            GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                /*    [
                                        'attribute' => 'image',
                                        'header' => 'Image',
                                        'value' => function($model) {
                                            if ($model->image != "") {
                                                $imgPath = Yii::$app->ManageRequest->getImage($model);
                                            } else {
                                                $imgPath = Yii::$app->request->baseUrl . '/img/no-image.jpg';
                                            }
                                            return $imgPath;
                                        },
                                        'format' => ['image', ['width' => '80', 'height' => '80']],
                                    ], */
                                    'id',
                                    'package_title',
//                                    'category_id',
                                    // 'sku',
                                    // 'title',
                                    [
                                        'attribute' => 'category_id',
                                        'header' => 'Category',
                                        'filter' => Html::activeDropDownList($searchModel, 'category_id', $options, ['class' => 'form-control ashik ', 'prompt' => 'All']),
                                        'value' => function($model) {
                                        //   /  if ($model->category->parent != $model->category->id) {

                                        //         $html = '<nav aria-label = "breadcrumb"> <ol class = "breadcrumb">';
                                        //         $catmodel = \common\models\Category::find()->where(['id' => $model->category_id])->one();

                                        //         $option_items = Yii::$app->SelectCategory->selectCategories($catmodel);
                                        //         $option_data = explode('-', $option_items);
                                        //         $option_data_array = array_reverse($option_data);
                                        //         if ($option_data_array != NULL) {
                                        //             $i = 1;
                                        //             $count = count($option_data_array);
                                        //             foreach ($option_data_array as $option_data_arr) {
                                        //                 $option_cat = \common\models\Category::find()->where(['id' => $option_data_arr])->one();
                                        //                 $caturl = Yii::$app->request->baseUrl . '/category/view?id=' . $option_data_arr;

                                        //                 if ($option_cat != NULL) {
                                        //                     if ($i == $count) {
                                        //                         $html .= '<li class="breadcrumb-item active" aria-current="page"><a href = "' . $caturl . '">' . $option_cat->category_name . '</a></li>';
                                        //                     } else {
                                        //                         $html .= '<li class = "breadcrumb-item"><a href = "' . $caturl . '">' . $option_cat->category_name . '</a></li>';
                                        //                     }
                                        //                     $i++;
                                        //                 }
                                        //             }
                                        //         }

                                        //         $html .= '</ol></nav>';
                                        //         return $html;
                                        //     } else {
                                        //         $caturl = Yii::$app->request->baseUrl . '/category/view?id=' . $model->category->id;

                                        //         return '<a href = "' . $caturl . '">' . $model->category->category_name . '</a>';
                                        //     } 
                                         // return "<span style=' text-transform: capitalize'>" . $model->franchise->first_name . ' ' . $model->franchise->last_name . '(' . $gfranchise->country0->country_name . ')' . "</span>";
                                        },
                                        'format' => 'html',
                                    ],
                                   [
                                        'attribute' => 'status',
                                        'header' => 'Status',
                                        'filter' => Html::activeDropDownList($searchModel, 'status', ['1' => 'Enable','0' => 'Disable'], ['class' => 'form-control ashik ', 'prompt' => 'All']),
                                        'value' => function($model) {
                                            $status = "";
                                            if($model->status == 1) {
                                                $status = "Enable";
                                            } else {
                                                $status = "Disbale";
                                            }
                                            return "<span style=' text-transform: capitalize'>".$status."</span>";
                                           
                                        },
                                        'format' => 'html',
                                        'visible' => \Yii::$app->user->identity->interface != 'merchant' ? true : false
                                    ], 
                                /*     [
                                        'attribute' => 'store',
                                        'label' => 'Store(Franchise)',
                                        'filter' => Html::activeDropDownList($searchModel, 'store', $get_franchise, ['class' => 'form-control ashik ', 'prompt' => 'All']),
                                        'value' => function($model) {
                                            return "<span style=' text-transform: capitalize'>" . $model->merchant->franchise->first_name . ' ' . $model->merchant->franchise->last_name . '(' . $model->merchant->franchise->country0->country_name . ')' . "</span>";
                                        },
                                        'format' => 'html',
                                    ], */
                                    //'canonical_name',
                                    //'image',
                                    //'gallery:ntext',
                                    //'sort_order',
                                    //'price',
                                    //'discount_type',
                                    //'discount_rate',
                                    //'requires_shipping',
                                    //'new_from',
                                    //'new_to',
                                    //'sale_from',
                                    //'sale_to',
                                    //'discount_from',
                                    //'discount_to',
                                    //'search_tag:ntext',
                                    //'related_products:ntext',
                                    //'stock_availability',
                                    //'is_featured',
                                    //'is_admin_approved',
                                    //'created_at',
                                    //'updated_at',
                                    //'updated_by',
                                    //'created_by',
                                    //'status',
                                    //'meta_title',
                                    //'meta_description:ntext',
                                    //'meta_keywords:ntext',
                                    //'tax_applicable',
                                    //'tax_amount',
                                    //'min_quantity',
                                    //'quantity',
                                    //'weight_class',
                                    //'weight',
                                    //'short_description:ntext',
                                    //'long_description:ntext',
                                    //'type',
                                    
                                    [
                                        'header' => \Yii::t('app', 'Actions'),
                                        'class' => '\yii\grid\ActionColumn',
                                        'contentOptions' => [
                                            'class' => 'table-actions'
                                        ],
                                        'template' => '{popup}{view} {update} {delete}',
                                        'buttons' => [
                                           /* 'popup' => 
                                                        function ($model)  {
                                                            
                                                            return Html::tag('span', '<i class="fa fa-sliders"></i>', ['class'=>'label ','data-toggle'=>"modal",
                                                            'data-target'=>"#package_content", 'data-id'=>'1', 'data-ds'=>'123']);
                                                        }, */
                                            'popup' => function ($url, $model) {

                                                    return Html::a(
                                                        '<i class="fa fa-sliders add_pkg_content"></i>', \yii\helpers\Url::to(['packages-date/save-package-date-price', 'id' => $model->id]), [
//                                                            'rel' => "tooltip",
//                                                            'data-original-title' => 'View this user',
                                                    'data-placement' => 'top',
                                                    'style' => 'margin-right: 10px'
                                                        ]
                                                    );
                                            },
                                            'view' => function ($url, $model) {
                                                return Html::a(
                                                                '<i class="fa fa-eye"></i>', \yii\helpers\Url::to(['view', 'id' => $model->id]), [
//                                                            'rel' => "tooltip",
//                                                            'data-original-title' => 'View this user',
                                                            'data-placement' => 'top',
                                                            'style' => 'margin-right: 10px'
                                                                ]
                                                );
                                            },
                                            'update' => function ($url, $model) {
                                                return Html::a(
                                                                '<i class="fa fa-edit"></i>', \yii\helpers\Url::to(['update', 'id' => $model->id]), [
//                                                            'rel' => "tooltip",
//                                                            'data-original-title' => 'Edit this user',
                                                            'data-placement' => 'top',
                                                            'style' => 'margin-right: 10px'
                                                                ]
                                                );
                                            },
                                            'delete' => function ($url, $model) {
                                                return Html::a(
                                                                '<i class="fa fa-trash-o"></i>', \yii\helpers\Url::to(['delete', 'id' => $model->id]), [
//                                                            'rel' => "tooltip",
//                                                            'data-original-title' => 'Delete this user?',
                                                            'data-placement' => 'top',
                                                            'data-pjax' => '0',
                                                            'data-confirm' => 'Are you sure you want to delete this item?',
                                                            'data-method' => 'post',
                                                            'style' => 'margin-right: 10px'
                                                                ]
                                                );
                                            },
                                        ]
                                    ],
                                ],
                            ]);
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>