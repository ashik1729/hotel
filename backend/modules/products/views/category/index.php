<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $model_path = str_replace(" ", "-", strtolower(Html::encode($this->title))) ?>


<div class="content category-index">
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
                    <div class="card-body">
                        <div class="material-datatables">
                            <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>
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
                            <?=
                            GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    'id',
                                    [
                                        'attribute' => 'parent',
                                        'header' => 'Parent Category',
                                        'filter' => Html::activeDropDownList($searchModel, 'parent', $options, ['class' => 'form-control ashik ', 'prompt' => 'All']),
                                        'value' => function($model) {
                                            if ($model->parent != $model->id) {
                                                $html = '<nav aria-label = "breadcrumb"> <ol class = "breadcrumb">';
                                                $option_items = Yii::$app->SelectCategory->selectCategories($model);
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
//                                                                $html .= '<li class="breadcrumb-item active" aria-current="page">' . $option_cat->category_name . '</li>';
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
//                                            return "<span style=' text-transform: capitalize'>" . $model->franchise->first_name . ' ' . $model->franchise->last_name . '(' . $gfranchise->country0->country_name . ')' . "</span>";
                                        },
                                        'format' => 'html',
                                    ],
                                    'category_name',
                                    [
                                        'attribute' => 'status',
                                        'header' => 'Status',
                                        'filter' => ['0' => 'Disabled', '1' => 'Enable'],
                                        'filterInputOptions' => ['class' => 'form-control ', 'id' => null, 'prompt' => 'All', 'data-live-search' => "true", 'title' => "Select a Status", 'data-hide-disabled' => "true"], // to change 'Todos' instead of the blank option
                                        'value' => function($data) {
                                            if ($data->status == 1) {
                                                return "<span style='color:green;font-weight:bold'>Enable</span>";
                                            } else {
                                                return "<span style='color:red;font-weight:bold'>Disable</span>";
                                            }
                                        },
                                        'format' => 'html',
                                    ],
//                                    'parent',
//                                    'category_name',
//                                    'description:ntext',
//                                    'canonical_name',
                                    //'image',
                                    //'gallery:ntext',
                                    //'search_tag:ntext',
                                    //'sort_order',
                                    //'header_visibility',
                                    //'status',
                                    //'created_at',
                                    //'created_by',
                                    //'updated_at',
                                    //'updated_by',
                                    //'meta_title',
                                    //'meta_description:ntext',
                                    //'meta_keywords:ntext',
                                    [
                                        'header' => \Yii::t('app', 'Actions'),
                                        'class' => '\yii\grid\ActionColumn',
                                        'contentOptions' => [
                                            'class' => 'table-actions'
                                        ],
                                        'template' => '{view} {update} {delete}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                return Html::a(
                                                                '<i class="fa fa-eye"></i>', \yii\helpers\Url::to(['view', 'id' => $model->id]), [
                                                            'rel' => "tooltip",
                                                            'data-original-title' => 'View this user',
                                                            'data-placement' => 'top',
                                                            'style' => 'margin-right: 10px'
                                                                ]
                                                );
                                            },
                                            'update' => function ($url, $model) {
                                                return Html::a(
                                                                '<i class="fa fa-edit"></i>', \yii\helpers\Url::to(['update', 'id' => $model->id]), [
                                                            'rel' => "tooltip",
                                                            'data-original-title' => 'Edit this user',
                                                            'data-placement' => 'top',
                                                            'style' => 'margin-right: 10px'
                                                                ]
                                                );
                                            },
                                            'delete' => function ($url, $model) {
                                                return Html::a(
                                                                '<i class="fa fa-trash-o"></i>', \yii\helpers\Url::to(['delete', 'id' => $model->id]), [
                                                            'rel' => "tooltip",
                                                            'data-original-title' => 'Delete this user?',
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

