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
                                    'id',
                                    [   'attribute' => 'package_title',
                                        'header' => 'Package Title',
                                        'value' => function($model) { 
                                            $title = isset($model->package_title)?$model->package_title:"";
                                            $title = strip_tags($title);
                                            return $title;
                                        }
                                    
                                    ],
                                    [
                                        'attribute' => 'category_id',
                                        'header' => 'Category',
                                        'filter' => Html::activeDropDownList($searchModel, 'category_id', $options, ['class' => 'form-control ashik ', 'prompt' => 'All']),
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
                                                        $caturl = Yii::$app->request->baseUrl . '/products/category/view?id=' . $option_data_arr;

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
                                                $caturl = Yii::$app->request->baseUrl . '/products/category/view?id=' . $model->category->id;

                                                return '<a href = "' . $caturl . '">' . $model->category->category_name . '</a>';
                                            } 
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
                                    ], 
                                    
                                    [
                                        'header' => \Yii::t('app', 'Actions'),
                                        'class' => '\yii\grid\ActionColumn',
                                        'contentOptions' => [
                                            'class' => 'table-actions'
                                        ],
                                        'template' => '{popup}{view} {update} {delete}',
                                        'buttons' => [
                                            'popup' => function ($url, $model) {

                                                return Html::a(
                                                '<i class="fa fa-sliders add_pkg_content"></i>', \yii\helpers\Url::to(['packages-date/list-package-details', 'id' => $model->id]), [
                                                'rel' => "tooltip",
                                                'data-original-title' => 'Add package date',
                                                'data-placement' => 'top',
                                                'style' => 'margin-right: 10px'
                                                    ]
                                                );
                                            },
                                            'view' => function ($url, $model) {
                                                return Html::a(
                                                                '<i class="fa fa-eye"></i>', \yii\helpers\Url::to(['view', 'id' => $model->id]), [
                                                           'rel' => "tooltip",
                                                           'data-original-title' => 'View',
                                                            'data-placement' => 'top',
                                                            'style' => 'margin-right: 5px'
                                                                ]
                                                );
                                            },
                                            'update' => function ($url, $model) {
                                                return Html::a(
                                                    '<i class="fa fa-edit"></i>', \yii\helpers\Url::to(['update', 'id' => $model->id]), [
                                                    'rel' => "tooltip",
                                                    'data-original-title' => 'Edit',
                                                    'data-placement' => 'top',
                                                    'style' => 'margin-right: 5px'
                                                        ]
                                                );
                                            },
                                            'delete' => function ($url, $model) {
                                                return Html::a(
                                                    '<i class="fa fa-trash-o"></i>', \yii\helpers\Url::to(['delete', 'id' => $model->id]), [
                                                    'rel' => "tooltip",
                                                    'data-original-title' => 'Delete this package?',
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