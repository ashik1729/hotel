<?php

use common\models\BookingTravellers;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\web\JsExpression;
use yii\widgets\Pjax;

/**
 *
 * @package    Material Dashboard Yii2
 * @author     CodersEden <hello@coderseden.com>
 * @link       https://www.coderseden.com
 * @copyright  2020 Material Dashboard Yii2 (https://www.coderseden.com)
 * @license    MIT - https://www.coderseden.com
 * @since      1.0
 */
?>

<?php
$formatJs = <<< 'JS'
var formatRepo = function (repo) {
    if (repo.loading) {
        return repo.text;
    }
    console.log(repo);
    var markup =
'<div class="row">' +
    '<div class="col-sm-5">' +
        '<img src="' + repo.image + '" class="img-rounded" style="width:30px" />' +
        '<b style="margin-left:5px">' + repo.text + '</b>' +
    '</div>' +
    '<div class="col-sm-3"><i class="fa fa-star"></i> ' + repo.short_description_en + '</div>' +
    '<div class="col-sm-3"><i class="fa fa-code-fork"></i> ' + repo.price + '</div>' +
'</div>';
//    if (repo.short_description_en) {
//      markup += '<p>' + repo.short_description_en + '</p>';
//    }
    return '<div style="overflow:hidden;">' + markup + '</div>';
};
var formatRepoSelection = function (repo) {
    return repo.text;
}
JS;

// Register the formatting script
$this->registerJs($formatJs, yii\web\View::POS_HEAD);

// script to parse the results into the format expected by Select2
$resultsJs = <<< JS
function (data, params) {
    params.page = params.page || 1;
    return {
        results: data.items,
        pagination: {
            more: (params.page * 30) < data.total_count
        }
    };
}
JS;
?>

<style>
    .summary {
        display: none;
    }

    table.table.table-striped.table-bordered {
        margin-bottom: 0px !important;
    }
</style>

<div class="content">
    <?php Pjax::begin(['id' => 'order_section']) ?>

    <div class="container-fluid">
        <div class="card ">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">account_box</i>
                </div>

                <h4 class="card-title">
                    <?= Html::encode('Booking') ?>
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
                        Html::a(Html::tag('b', 'add', ['class' => 'material-icons']), ['create'], [
                            'class' => 'btn btn-xs btn-success btn-round btn-fab',
                            'rel' => "tooltip",
                            'data' => [
                                'placement' => 'bottom',
                                'original-title' => 'Create'
                            ],
                        ])
                        ?>

                        <?php
                        //echo
                        //                        Html::a(Html::tag('b', 'create', ['class' => 'material-icons']), ['update', 'id' => $model->id], [
                        //                            'class' => 'btn btn-xs btn-success btn-round btn-fab',
                        //                            'rel' => "tooltip",
                        //                            'data' => [
                        //                                'placement' => 'bottom',
                        //                                'original-title' => 'Edit Order'
                        //                            ],
                        //                        ])
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
                                'original-title' => 'Delete Order'
                            ],
                        ])
                        ?>
                    </div>
                </h4>
                <h4 class="card-title font-weight-bolder order_title mt-5">Booking ID : <?php echo "#" . $model->id; ?> <span class="float-right"><?php echo $model->orderStatus->name; ?></span></h4>

            </div>
            <div class="row mt-5">
                <div class="col-sm-4">
                    <div class="card-body">

                        <div class="card-header card-header-rose text-center m-0 p-1 font-weight-bold">
                            <h4 class="card-title">Booking Details <span target=".ship_text" class="float-right ml-2 pointer" data-toggle="modal" data-target="#order_edit"><i class="fa fa-edit"></i></span></h4>

                        </div>
                        <?=
                        DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                [
                                    'attribute' => 'created_at',
                                    'label' => 'Order Date',
                                    'value' => function ($data) {
                                        if ($data->created_at) {
                                            return date('d M Y H:i:s A', strtotime($data->created_at));
                                        } else {
                                            return '';
                                        }
                                    },
                                    'format' => 'html'
                                ],
                                [
                                    'attribute' => 'total_amount',
                                    'label' => 'Order amount',
                                    'value' => function ($data) {
                                        return Yii::$app->Currency->convert($data->total_amount, $data->store);
                                    },
                                    'format' => 'html'
                                ],
                                // [
                                //     'attribute' => 'amount_paid',
                                //     'label' => 'Amount Paid',
                                //     'value' => function ($data) {
                                //         return Yii::$app->Currency->convert($data->amount_paid, $data->store);
                                //     },
                                //     'format' => 'html'
                                // ],
                                [
                                    'attribute' => 'payment_method',
                                    'label' => 'Payment Method',
                                    'value' => function ($data) {
                                        $result = $data->payment_method == 1 ? "Cash On Delivery" : ($data->payment_method == 2 ? "Card" : "Online Banking");
                                        $result .= ' - ' . ($data->payment_status == 0 ? "<span class='hash'>Pending</span>" : ($data->payment_status == 1 ? "<span class='green'>Success</span>" : "<span class='red'>Failed</span>"));
                                        return $result;
                                    },
                                    'format' => 'html'
                                ],
                                'transaction_id',

                            ],
                        ])
                        ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card-body">
                        <div class="card-header card-header-rose text-center m-0 p-1 font-weight-bold">
                            <h4 class="card-title">Customer Details</h4>

                        </div>
                        <?=
                        DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                //                        'id',
                                [
                                    'attribute' => 'user_id',
                                    'value' => function ($data) {
                                        if ($data->user_id != 0) {
                                            $get_user = \common\models\User::findOne(['id' => $data->user_id]);
                                            return $get_user->first_name . ' ' . $get_user->last_name;
                                        } else {
                                            return '';
                                        }
                                    },
                                    'format' => 'html'
                                ],
                                [
                                    'attribute' => 'store',
                                    'label' => 'Store',
                                    'value' => function ($data) {
                                        if ($data->store != 0) {
                                            return $data->store0->first_name . ' ' . $data->store0->last_name;
                                        } else {
                                            return 'Not Set';
                                        }
                                    },
                                    'format' => 'html'
                                ],
                                [
                                    'attribute' => 'user_id',
                                    'label' => 'Email',
                                    'value' => function ($data) {
                                        return $data->user->email;
                                    },
                                    'format' => 'html'
                                ],
                                [
                                    'attribute' => 'user_id',
                                    'label' => 'Phone',
                                    'value' => function ($data) {
                                        return $data->user->mobile_number;
                                    },
                                    'format' => 'html'
                                ],
                            ],
                        ])
                        ?>


                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="card-body">
                        <div class="card-header card-header-rose text-center m-0 p-1 font-weight-bold">
                            <h4 class="card-title">Options </h4>

                        </div>
                        <table id="w1" class="table table-striped table-bordered detail-view">
                            <tbody>
                                <tr>
                                    <th>Invoice</th>
                                    <td>
                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#invoiceModal"> <i class="material-icons">download</i></button>
                                    </td>
                                </tr>

                            </tbody>
                        </table>

                    </div>
                </div>


                <div class="col-sm-12">
                    <div class="card-body">
                        <div class="card-header card-header-rose text-center m-0 p-1 font-weight-bold hash_bg">
                            <h4 class="card-title"><span target=".bill_text" class="float-left ml-2 copy_bord"><i class="fa fa-copy"></i></span> Billing Address <span target=".ship_text" class="float-right ml-2 edit_bill_address" address_id="<?php echo $model->bill_address; ?>" order_id="<?php echo $model->id; ?>"><i class="fa fa-edit"></i></span></h4>

                        </div>
                        <p class="bill_text" style="display: none">
                            <?php
                            if ($model->bill_address != 0) {
                                echo $model->billAddress->first_name . ' ' . $model->billAddress->last_name . '<br/>';
                                echo $model->billAddress->streat_address . '<br/>';
                                echo $model->billAddress->city0->name_en . '<br/>';
                                echo $model->billAddress->state0->state_name . '<br/>';
                                echo $model->billAddress->country0->country_name . '<br/>';
                                echo $model->billAddress->postcode;
                            }
                            ?>
                        </p>
                        <?=
                        DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                //                        'id',
                                [
                                    'attribute' => 'bill_address',
                                    'label' => 'Name',
                                    'value' => function ($data) {
                                        if ($data->bill_address != 0) {
                                            return $data->billAddress->first_name . ' ' . $data->billAddress->last_name;
                                        } else {
                                            return '';
                                        }
                                    },
                                    'format' => 'html'
                                ],
                                [
                                    'attribute' => 'bill_address',
                                    'label' => 'Country',
                                    'value' => function ($data) {
                                        if ($data->bill_address != 0) {
                                            return $data->billAddress->country0->country_name;
                                        } else {
                                            return '';
                                        }
                                    },
                                    'format' => 'html'
                                ],
                                // [
                                //     'attribute' => 'bill_address',
                                //     'label' => 'State',
                                //     'value' => function ($data) {
                                //         if ($data->bill_address != 0) {
                                //             return $data->billAddress->state0->state_name;
                                //         } else {
                                //             return '';
                                //         }
                                //     },
                                //     'format' => 'html'
                                // ],
                                // [
                                //     'attribute' => 'bill_address',
                                //     'label' => 'City',
                                //     'value' => function ($data) {
                                //         if ($data->bill_address != 0) {
                                //             return $data->billAddress->city0->name_en;
                                //         } else {
                                //             return '';
                                //         }
                                //     },
                                //     'format' => 'html'
                                // ],
                                // [
                                //     'attribute' => 'bill_address',
                                //     'label' => 'Streat Address',
                                //     'value' => function ($data) {
                                //         if ($data->bill_address != 0) {
                                //             return $data->billAddress->streat_address;
                                //         } else {
                                //             return '';
                                //         }
                                //     },
                                //     'format' => 'html'
                                // ],
                                // [
                                //     'attribute' => 'bill_address',
                                //     'label' => 'Post Code',
                                //     'value' => function ($data) {
                                //         if ($data->bill_address != 0) {
                                //             return $data->billAddress->postcode;
                                //         } else {
                                //             return '';
                                //         }
                                //     },
                                //     'format' => 'html'
                                // ],
                                [
                                    'attribute' => 'bill_address',
                                    'label' => 'Phone Number',
                                    'value' => function ($data) {
                                        if ($data->bill_address != 0) {
                                            return $data->billAddress->phone_number;
                                        } else {
                                            return '';
                                        }
                                    },
                                    'format' => 'html'
                                ],
                                [
                                    'attribute' => 'bill_address',
                                    'label' => 'Email',
                                    'value' => function ($data) {
                                        if ($data->bill_address != 0) {
                                            return $data->billAddress->email;
                                        } else {
                                            return '';
                                        }
                                    },
                                    'format' => 'html'
                                ],
                            ],
                        ])
                        ?>


                    </div>
                </div>

                <div class="col-sm-12 ">
                    <div class="card-body">
                        <div class="card-header card-header-rose text-center m-0 p-1 font-weight-bold">
                            <h4 class="card-title">Order Items</h4>

                        </div>
                        <div class="material-datatables">
                            <?php // echo $this->render('_search', ['model' => $searchModel]);              
                            ?>

                            <?=
                            GridView::widget([
                                'dataProvider' => $dataProvider,
                                //                                'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    //                                    'id',
                                    //                                    'order_id',
                                    /*    [
                                        'attribute' => 'product_id',
                                        'value' => function ($data) {
                                            if ($data->product_id != 0) {
                                                return Html::a($data->product->product_name_en, Url::toRoute(['/products/products-services/view', 'id' => $data->product_id]), ['target' => '_blank', 'class' => 'font-weight-bolder']);
                                            } else {
                                                return '';
                                            }
                                        },
                                        'format' => 'raw'
                                    ], */
                                    [
                                        'attribute' => 'product_id',
                                        'header' => 'Package Name',
                                        'label' => 'Deliery Date',
                                        'value' => function ($data) {
                                            if ($data->product_id) {
                                                return $data->product->package_title;
                                            } else {
                                                return '--';
                                            }
                                        },
                                        'format' => 'raw'
                                    ],
                                    // 'quantity',
                                    [
                                        'attribute' => 'date',
                                        'label' => 'Deliery Date',
                                        'value' => function ($data) {
                                            if ($data->date) {
                                                return $data->date;
                                            } else {
                                                return '--';
                                            }
                                        },
                                        'format' => 'raw'
                                    ],
                                    [
                                        'attribute' => 'no_adults',
                                        'label' => 'Adults',
                                        'value' => function ($data) {
                                            $html = "";
                                            if ($data->no_adults) {
                                                $getTravelers = BookingTravellers::find()->where(['order_product_id'=>$data->id])->all();
                                                $html .= "<h5>Total " . $data->no_adults . " Adults</h5>";
                                                $html .= "<ul>";
                                                if ($getTravelers != NULL) {
                                                    foreach ($getTravelers as $getTraveler) {
                                                        $html .= "<li>".$getTraveler->first_name." ".$getTraveler->last_name."</li>";
                                                    }
                                                }
                                                $html .= "</ul>";
                                                return $html;
                                            } else {
                                                return '--';
                                            }
                                        },
                                        'format' => 'html'
                                    ],
                                    'no_children',
                                    [
                                        'attribute' => 'status',
                                        'label' => 'Order Status',
                                        'value' => function ($data) {
                                            if ($data->status == 0) {
                                                return "deleted";
                                            } else {
                                                $get_latest_order_history = \common\models\OrderHistory::find()->where(['order_product_id' => $data->id])->orderBy('created_at DESC')->one();
                                                if ($get_latest_order_history != NULL) {
                                                    return $get_latest_order_history->orderStatus->name . '<br/><span class="history_comment" font-size="10px;">' . $get_latest_order_history->order_status_custome_comment . '</span>';
                                                } else {
                                                    return $data->orderStatus->name;
                                                }
                                            }
                                        },
                                        'format' => 'html'
                                    ],
                                    [
                                        'attribute' => 'amount',
                                        'label' => 'Unit Price',
                                        'value' => function ($data) {
                                            return Yii::$app->Currency->convert($data->amount, $data->order->store);
                                        },
                                        'format' => 'html'
                                    ],
                                    [
                                        'attribute' => 'amount',
                                        'label' => 'Total',
                                        'value' => function ($data) {
                                            return Yii::$app->Currency->convert(($data->amount * $data->no_adults), $data->order->store);
                                        },
                                        'format' => 'html'
                                    ],
                                    //'created_at',
                                    //'updated_at',
                                    //'created_by',
                                    //'updated_by',
                                    //                                    [
                                    //                                        'class' => 'yii\grid\ActionColumn',
                                    //                                        'header' => 'History ',
                                    //                                        'template' => '{my_button_twelve}',
                                    //                                        'buttons' => [
                                    //                                            'my_button_twelve' => function ($url, $orderproductmodel, $key) {
                                    //
                                    //                                                return '<i order_product_id="' . $orderproductmodel->id . '"  class = "fa fa-history add_history"></i>';
                                    //                                            },
                                    //                                        ]
                                    //                                    ],
                                    [
                                        'header' => \Yii::t('app', 'Actions'),
                                        'class' => '\yii\grid\ActionColumn',
                                        'contentOptions' => [
                                            'class' => 'table-actions'
                                        ],
                                        'template' => '{history}  {update} {delete}',
                                        'buttons' => [
                                            'history' => function ($url, $orderproductmodel, $key) {

                                                return '<i order_product_id="' . $orderproductmodel->id . '"  class = "fa fa-history add_history"></i>';
                                            },
                                            'update' => function ($url, $orderproductmodel, $key) {

                                                return '<i  order_product_id="' . $orderproductmodel->id . '"  class = "fa fa-edit load_item"></i>';
                                            },
                                            //                                            'history' => function ($url, $model) {
                                            //                                                return Html::a(
                                            //                                                                '<i class = "fa fa-history"></i>', \yii\helpers\Url::to("#"), [
                                            //                                                            'rel' => "tooltip",
                                            //                                                            'data-original-title' => 'Add Order History',
                                            //                                                            'data-placement' => 'top',
                                            //                                                            'data-pjax' => '0',
                                            //                                                            'data-method' => 'post',
                                            //                                                            'data-method' => 'post',
                                            //                                                            'class' => 'add_history',
                                            //                                                            'style' => 'margin-right: 10px'
                                            //                                                                ]
                                            //                                                );
                                            //                                            },
                                            //                                            'view' => function ($url, $model) {
                                            //                                                return Html::a(
                                            //                                                                '<i class = "fa fa-eye"></i>', \yii\helpers\Url::to(['view', 'id' => $model->id]), [
                                            //                                                            'rel' => "tooltip",
                                            //                                                            'data-original-title' => 'View this user',
                                            //                                                            'data-placement' => 'top',
                                            //                                                            'style' => 'margin-right: 10px'
                                            //                                                                ]
                                            //                                                );
                                            //                                            },
                                            //                                            'update' => function ($url, $model) {
                                            //                                                return Html::a(
                                            //                                                                '<i class = "fa fa-edit"></i>', \yii\helpers\Url::to(['update', 'id' => $model->id]), [
                                            //                                                            'rel' => "tooltip",
                                            //                                                            'data-original-title' => 'Edit this user',
                                            //                                                            'data-placement' => 'top',
                                            //                                                            'style' => 'margin-right: 10px'
                                            //                                                                ]
                                            //                                                );
                                            //                                            },
                                            'delete' => function ($url, $orderproductmodel, $key) {

                                                return '<i  order_product_id="' . $orderproductmodel->id . '"  class = "fa fa-trash-o delete_item"></i>';
                                            },
                                            //                                            'delete' => function ($url, $model) {
                                            //                                                return Html::a(
                                            //                                                                '<i class = "fa fa-trash-o "></i>', \yii\helpers\Url::to(['delete', 'id' => $model->id]), [
                                            ////                                                            'rel' => "tooltip",
                                            //                                                            'data-original-title' => 'Delete this Item From Order?',
                                            //                                                            'data-placement' => 'top',
                                            //                                                            'data-pjax' => '0',
                                            //                                                            'data-confirm' => 'Are you sure you want to delete this item?',
                                            //                                                            'data-method' => 'post',
                                            //                                                            'style' => 'margin-right: 10px'
                                            //                                                                ]
                                            //                                                );
                                            //                                            },
                                        ]
                                    ],
                                ],
                            ]);
                            ?>
                            <table class="table table-striped table-bordered total_form">
                                <tbody>
                                    <tr data-key="5">
                                        <td colspan="11" class="text-right"><span class="font-weight-bolder">Sub Total</span></td>
                                        <td colspan="2" class="text-left"><?php echo Yii::$app->Currency->convert(($model->total_amount - $model->shipping_charge), $model->store); ?> </td>
                                    </tr>

                                    <tr data-key="5">
                                        <td colspan="11" class="text-right"><span class="font-weight-bolder">Shipping Charge</span></td>
                                        <td colspan="2" class="text-left"><?php echo Yii::$app->Currency->convert($model->shipping_charge, $model->store); ?> </td>
                                    </tr>
                                    <tr data-key="5">
                                        <td colspan="11" class="text-right"><span class="font-weight-bolder">Total</span></td>
                                        <td colspan="2" class="text-left"><?php echo Yii::$app->Currency->convert($model->total_amount, $model->store); ?> </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- <table class="table table-striped table-bordered ">
                                <tbody>
                                    <tr data-key="5">
                                        <td colspan="7" class="text-right">
                                            <div class="card-body">
                                                <button class="btn btn-primary" data-toggle="modal" data-target="#addProductModal">Add products</button>
                                                <!-- <button class="btn btn-primary">Update Shipping</button>
                                                <button class="btn btn-primary">Add Coupons</button>
                                                <button class="btn btn-primary">Refund</button> -->
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table> -->
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>

    <?=
    $this->render('_address', [
        'model' => $model,
        'type' => 1,
        'name' => "updateShipAddressModal",
        'addressmodel' => $model->ship_address ? common\models\UserAddress::find()->where(['id' => $model->ship_address])->one() : new common\models\UserAddress()
    ])
    ?>
    <?=
    $this->render('_address', [
        'model' => $model,
        'type' => 2,
        'name' => "updateBillAddressModal",
        'addressmodel' => $model->bill_address ? common\models\UserAddress::find()->where(['id' => $model->bill_address])->one() : new common\models\UserAddress()
    ])
    ?>
    <?=
    $this->render('_order_edit', [
        'model' => $model,
    ])
    ?>
    <?=
    $this->render('_order_product_edit', [
        'model' => $model,
        'id' => "order_product_edit"
    ])
    ?>
    <?php Pjax::end()
    ?>
</div>

<?php $producturl = \yii\helpers\Url::to(['/ajax/get-item-list?store_id=' . $model->store]); ?>

<div class="modal fade" id="invoiceModal" tabindex="-1" role="dialog" aria-labelledby="editAttributeValueModal" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="editAttributeValueModalTitle">Download Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php Pjax::begin(['id' => 'invoice_order']) ?>
                <?php $orderProductsDataInvoiceCount = common\models\OrderInvoice::find()->where(['order_id' => $model->id])->andWhere("invoice IS NOT NULL")->count(); ?>
                <div class="card-body">
                    <button <?= $orderProductsDataInvoiceCount == 0 ? "disabled" : ""; ?> class="btn btn-primary btn-sm download_all_invoice invoice_download" order_id="<?php echo $model->id; ?>" merchant_id="0"> Download All Invoice <i class="material-icons">download</i></button>
                </div>
                <div class="clearfix"></div>
                <?php
                $order_productquery = \common\models\OrderProducts::find()->where(['order_id' => $model->id]);
                $order_products_data = $order_productquery->asArray()->all();
                $merchant_lists = array_unique(array_column($order_products_data, 'merchant_id'));
                ?>
                <div class="card-body invoice_accord">
                    <div id="accordion" role="tablist">
                        <?php if ($merchant_lists != NULL) { ?>
                            <?php
                            foreach ($merchant_lists as $merchant_list) {
                                $order_merchant_invoice = common\models\OrderInvoice::find()->where(['order_id' => $model->id, 'merchant_id' => $merchant_list])->andWhere("invoice IS NOT NULL")->one();
                                $merchant = common\models\Merchant::findOne(['id' => $merchant_list]);
                                $orderProductsDatas = \common\models\OrderProducts::find()->where(['order_id' => $model->id, 'merchant_id' => $merchant_list])->all();
                            ?>
                                <div class="card-collapse ">
                                    <div class="card-header" role="tab" id="invoiceaccor_<?php echo $merchant->id; ?>">
                                        <h5 class="mb-0">
                                            <a data-toggle="collapse" href="#collapse_<?php echo $merchant->id; ?>" aria-expanded="true" aria-controls="collapse_<?php echo $merchant->id; ?>" class="collapsed">
                                                <?php echo $merchant->business_name; ?> <?php echo $merchant->country0->country_name; ?>
                                                <i class="material-icons float-right">keyboard_arrow_down</i>
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapse_<?php echo $merchant->id; ?>" class="collapse show" role="tabpanel" aria-labelledby="invoiceaccor_<?php echo $merchant->id; ?>" data-parent="#accordion" style="">
                                        <div class="card-body ">
                                            <div class="row">
                                                <div class="col-sm-6 mb-3">
                                                    <p>Invoice No: <?= $order_merchant_invoice ? $order_merchant_invoice->invoice : ' <button class="btn btn-primary btn-sm genrate_invoice" order_id="' . $model->id . '" merchant_id="' . $merchant->id . '">  Generate <i class="material-icons">upgrade</i></button>'; ?></p>
                                                    <p>Invoice Date : <?= $order_merchant_invoice ? date('Y-m-d H:i A', strtotime($order_merchant_invoice->invoice_date)) : ""; ?></p>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <button order_id="<?php echo $model->id; ?>" merchant_id="<?php echo $merchant->id; ?>" class="btn btn-primary btn-sm invoice_download float-right" <?= $order_merchant_invoice->invoice ? "" : "disabled"; ?>> <i class="material-icons">download</i></button>
                                                </div>
                                                <div class="col-sm-12">
                                                    <?php if ($orderProductsDatas != NULL) { ?>
                                                        <table id="invoice_order_products" class="table table-striped table-bordered detail-view invoice_order_products">
                                                            <tbody>
                                                                <tr>
                                                                    <th>Item</th>
                                                                    <th>SKU</th>
                                                                    <th>Quantity</th>

                                                                </tr>
                                                                <?php foreach ($orderProductsDatas as $orderProductsData) { ?>
                                                                    <tr>
                                                                        <?php /*  <td><?= $orderProductsData->product->product_name_en; ?> </td>
                                                                        <td><?= $orderProductsData->product->sku; ?></td>  */ ?>
                                                                        <td><?= $orderProductsData->quantity; ?></td>

                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>

                    </div>
                </div>


                <p class="attr_error"></p>


                <?php Pjax::end()
                ?>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="editAttributeValueModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAttributeValueModalTitle">Update Product Attributes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php $fromaddProduct = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'form_product'], 'action' => 'add-order-products?id=' . $model->id]); ?>
            <div class="modal-body">
                <p class="attr_error"></p>
                <div class="row ">


                    <div class="col-sm-12">
                        <div class="form-group bmd-form-group">
                            <?php
                            echo $fromaddProduct->field($order_products, 'product_id')->widget(Select2::classname(), [
                                'options' => ['placeholder' => 'Search for a Product/Service', 'class' => 'change_product'],
                                'theme' => Select2::THEME_MATERIAL,
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'initialize' => true,
                                    'minimumInputLength' => 1,
                                    'ajax' => [
                                        'url' => $producturl,
                                        'dataType' => 'json',
                                        'delay' => 250,
                                        'data' => new JsExpression('function(params) { return {q:params.term, page: params.page}; }'),
                                        'processResults' => new JsExpression($resultsJs),
                                        'cache' => true
                                    ],
                                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                    'templateResult' => new JsExpression('formatRepo'),
                                    'templateSelection' => new JsExpression('formatRepoSelection'),
                                ],
                            ]);
                            ?>

                            <?= $fromaddProduct->field($order_products, 'order_id')->hiddenInput(['value' => $model->id])->label(FALSE) ?>
                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class=" col-sm-12">
                        <div class="booking_date " style="display:none">
                            <?= $fromaddProduct->field($order_products, 'date')->textInput(['type' => 'date', 'id' => 'calenders', 'class' => 'change_date form-control']) ?>

                        </div>
                        <div class="booking_slots ">

                        </div>
                    </div>
                    <div class=" col-sm-12">
                        <div class="product_attribute ">

                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group bmd-form-group">
                            <?= $fromaddProduct->field($order_products, 'quantity')->textInput(['id' => 'input_update_attr_qty']) ?>
                            <div class="help-block"></div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary save_update_attr">Save </button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<div class="modal fade" id="orderHistoryModal" tabindex="-1" role="dialog" aria-labelledby="editAttributeValueModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAttributeValueModalTitle">Order History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php $orderhistoryform = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'form_history'], 'action' => 'add-order-history?id=' . $model->id]); ?>
            <div class="modal-body">
                <div class="product_history"></div>
                <p class="attr_error"></p>
                <div class="row ">


                    <div class="col-sm-12">
                        <div class="form-group bmd-form-group">
                            <?= $orderhistoryform->field($orderhistorymodel, 'order_status')->dropDownList(ArrayHelper::map(\common\models\OrderStatus::find()->all(), 'id', 'name'), ['prompt' => '', 'class' => 'form-control select_country']); ?>
                            <?= $orderhistoryform->field($orderhistorymodel, 'order_product_id')->hiddenInput(['class' => 'order_product_id'])->label(FALSE) ?>
                            <div class="help-block"></div>
                        </div>
                    </div>


                    <div class="col-sm-12">
                        <div class="form-group bmd-form-group">
                            <?= $orderhistoryform->field($orderhistorymodel, 'order_status_custome_comment')->textarea(['rows' => '3']) ?>
                            <div class="help-block"></div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary save_update_history">Save </button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>

<!--<div class="row">
    <button class="btn btn-primary btn-block" onclick="md.showNotification('bottom', 'center', 'ashik', '2')">Bottom Right</button>
</div>-->
<script>


</script>
<?php
$this->registerJs(
    <<< EOT_JS_CODE
    $(document).ready(function () {

         $(document.body).on('click', '.delete_item', function (e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this item?')) {
            var order_product_id = $(this).attr('order_product_id');
            $.ajax({
                url: basepath + "/order/orders/delete-order-products?id="+order_product_id,
                type: "POST",
//                data: {order_product_id: order_product_id},
                success: function (data) {
                    var obj = JSON.parse(data);
                    if (obj.status == 200) {
                        $.pjax.reload({container: '#order_section', async: false, timeout: false});
                        md.showNotification('bottom', 'center', 'Item Deleted', '3');
                        $('.loader-wrapp').hide();
                    } else {
                        $('.loader-wrapp').hide();
                    }
                },
                error: function () {
                    alert("Something went wrong");
                    $('.loader-wrapp').hide();
                }
            });
        }
    });

          $(document.body).on('click', '.form_update_product_btn', function (e) {

        $('.loader-wrapp').show();
        var form = $(".form_update_product");
        e.preventDefault();
        if (form.find('.has-error').length)
        {
            return false;
        }
        var formData = form.serialize();
        $.ajax({
            url: form.attr("action"),
            type: "POST",
            data: formData,
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj.status == 200) {
                    $.pjax.reload({container: '#order_section', async: false, timeout: false});
                    md.showNotification('bottom', 'center', 'Order Updated', '3');
                    $('#order_product_edit').modal('hide');
                    $('.loader-wrapp').hide();
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                } else {
                    $('.loader-wrapp').hide();

                }
            },
            error: function () {

                alert("Something went wrong");
                $('.loader-wrapp').hide();

            }

        });

    }).on('submit', function (e) {

        e.preventDefault();

    });
 $(document.body).on("click", ".load_item", function (e) {
        $('.loader-wrapp').show();
        var order_id = "$model->id";
        var order_product_id = $(this).attr('order_product_id');
        e.preventDefault();
        $.ajax({
            url: basepath + "/order/orders/load-order-product",
            type: "POST",
            data: {order_id: order_id, order_product_id: order_product_id},
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj.status == 200) {
                    $("#order_product_edit").find(".modal-content-data").html(obj.message);
                    $("#order_product_edit").modal("show");
                    $('.loader-wrapp').hide();
                } else {
                    $('.loader-wrapp').hide();
                }
            },
            error: function () {
                alert("Something went wrong");
                $('.loader-wrapp').hide();
            }
        });
    });

         $(document.body).on('beforeSubmit', '.form_update_order', function (e) {
        e.preventDefault();
        $('.loader-wrapp').show();
        var form = $(this);
        if (form.find('.has-error').length)
        {
            return false;
        }
        var formData = form.serialize();
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: formData,
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj.status == 200) {
                    $.pjax.reload({container: '#order_section', async: false, timeout: false});
                    md.showNotification('bottom', 'center', 'Order Updated', '3');
                    $('#order_edit').modal('hide');
                    $('.loader-wrapp').hide();
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                } else {
                    $('.loader-wrapp').hide();

                }
            },
            error: function () {

                alert("Something went wrong");
                $('.loader-wrapp').hide();

            }

        });

    }).on('submit', function (e) {

        e.preventDefault();

    });


 $(document.body).on('beforeSubmit','.form_update_address', function (e) {
          e.preventDefault();
        $('.loader-wrapp').show();
        var form = $(this);
        if (form.find('.has-error').length)
        {
            return false;
        }
        var formData = form.serialize();
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: formData,
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj.status == 200) {
                    $.pjax.reload({container: '#order_section', async: false, timeout: false});
                    md.showNotification('bottom', 'center', 'Address Updated', '3');
                    $('.user_address_modal').modal('hide');
                    $('.loader-wrapp').hide();
        $('body').removeClass('modal-open');
$('.modal-backdrop').remove();
                } else {
                $('.loader-wrapp').hide();

                }
            },
            error: function () {

                alert("Something went wrong");
                $('.loader-wrapp').hide();

            }

        });

    }).on('submit', function (e) {

        e.preventDefault();

    });
  $(document.body).on("click", ".edit_ship_address", function (e) {
        $('.loader-wrapp').show();
        $("#updateShipAddressModal").modal("show");
                $('.loader-wrapp').hide();

    });
  $(document.body).on("click", ".edit_bill_address", function (e) {
        $('.loader-wrapp').show();
        $("#updateBillAddressModal").modal("show");
                $('.loader-wrapp').hide();

    });

                $(document.body).on("click", ".copy_bord", function (e) {
                var element = $(this).attr('target');
                var temp = $("<input>");
                $("body").append(temp);
                temp.val($(element).text()).select();
                document.execCommand("copy");
                md.showNotification('bottom', 'center', 'Copy To Clipboard', '3');
                temp.remove();
        });
function showNotification(from, align){

  $.notify({
      icon: "add_alert",
      message: "Welcome to ashik <b>Material Dashboard</b> - a beautiful freebie for every web developer."

  },{
      type: 'success',
      timer: 4000,
      placement: {
          from: from,
          align: align
      }
  });
}

          $(document.body).on("click", ".invoice_download", function (e) {
        $('.loader-wrapp').show();
        var order_id = $(this).attr('order_id');
        var merchant_id = $(this).attr('merchant_id');
        e.preventDefault();
        $.ajax({
            url: basepath + "/order/orders/download-invoice",
            type: "POST",
            data: {order_id: order_id, merchant_id: merchant_id},
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj.status == 200) {
//        window.location = obj.message;
                    window.open(obj.message, '_blank');
                    $('.loader-wrapp').hide();
                } else {
                    $('.loader-wrapp').hide();
                }
            },
            error: function () {

                alert("Something went wrong");
                $('.loader-wrapp').hide();


            }

        });

    });



        $(document.body).on("click",".genrate_invoice",function(e){
        $('.loader-wrapp').show();
        var order_id = $(this).attr('order_id');
        var merchant_id = $(this).attr('merchant_id');
            e.preventDefault();
          $.ajax({
           url: basepath + "/order/orders/generate-invoice",
            type: "POST",
            data: {order_id: order_id,merchant_id:merchant_id},
            success: function (data) {
            var obj = JSON.parse(data);
                if (obj.status == 200) {
                  $.pjax.reload({container: '#invoice_order', async: false,timeout : false});
                    $('.loader-wrapp').hide();
                    md.showNotification('bottom', 'center', 'Invoice Generated', '3');
                } else {
        $('.loader-wrapp').hide();
md.showNotification('bottom', 'center', 'Invoice Not Genrated', '2');

                }
            },
            error: function () {

                alert("Something went wrong");
        $('.loader-wrapp').hide();
        md.showNotification('bottom', 'center', 'Invoice Not Genrated', '2');


            }

        });

        });
        $(document.body).on("click",".add_history",function(e){
        var order_product_id = $(this).attr('order_product_id');
        $('.order_product_id').val(order_product_id);

            e.preventDefault();
          $.ajax({
           url: basepath + "/order/orders/get-order-product-history",
            type: "POST",
            data: {order_product_id: order_product_id},
            success: function (data) {
            var obj = JSON.parse(data);
                if (obj.status == 200) {

                  $("#orderHistoryModal").find('.product_history').html(obj.message.data);
                  $("#orderHistoryModal").modal("show");
                } else {

                }
            },
            error: function () {

                alert("Something went wrong");

            }

        });

        });
    $('.form_product').on('beforeSubmit', function (e) {
        var form = $(this);
        if (form.find('.has-error').length)
        {
            return false;
        }
        var formData = form.serialize();
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: formData,
            success: function (data) {
            var obj = JSON.parse(data);
                if (obj.status == 200) {
                  $('#addProductModal').modal('hide');
                  $(".form_product")[0].reset();
                  $.pjax.reload({container: '#order_section', async: false,timeout : false});

                } else {

                }
            },
            error: function () {

                alert("Something went wrong");

            }

        });

    }).on('submit', function (e) {

        e.preventDefault();

    });
    $('.form_history').on('beforeSubmit', function (e) {
        var form = $(this);
        if (form.find('.has-error').length)
        {
            return false;
        }
        var formData = form.serialize();
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: formData,
            success: function (data) {
            var obj = JSON.parse(data);
                if (obj.status == 200) {
                  $('#orderHistoryModal').modal('hide');
                  $(".form_history")[0].reset();
                  $.pjax.reload({container: '#order_section', async: false,timeout : false});

                } else {

                }
            },
            error: function () {

                alert("Something went wrong");

            }

        });

    }).on('submit', function (e) {

        e.preventDefault();

    });
    $(document.body).on('change','.change_product',function(){
        var product_id = $(this).val();

        $.ajax({
            url: basepath + "/ajax/get-product-attributes",
            type: "POST",
            data: {product_id: product_id},

            success: function (data)
            {


                            var obj = JSON.parse(data);
                            if (obj.status == 200) {
                                if(obj.message.product_type == 2 || obj.message.product_type == 3){
                                    $('.booking_date').show();
                                }else{
                                $('.booking_date').hide();
                                  $('.booking_slots').html("");
                                }
                                    $('.product_attribute').html(obj.message.attributes);
                            } else {

                            }
            },
            error: function (e) {
                console.log(e);
            }
        });
    });
//  $('#calender').datetimepicker();
    var old_date = $('.change_date').val();

    $(document.body).on('change','.change_date',function(){
        var product_id = $('.change_product').val();
        var date = $(this).val();
        if(date !== old_date){
    old_date = date;
                $.ajax({
            url: basepath + "/ajax/get-product-available-slots",
            type: "POST",
            data: {product_id: product_id,date: date},

            success: function (data)
            {


                            var obj = JSON.parse(data);
                            if (obj.status == 200) {

                                    $('.booking_slots').html(obj.message.booking_slots);
                            } else {

                            }
            },
            error: function (e) {
                console.log(e);
            }
        });
    return true;
  }else{

          return false;
        }

        console.log(date);

    });
        var total_calc = $('.total_form').find('tbody').html();
        $("#w4").find("tbody").append(total_calc);
        $('.total_form').remove();

     var old_date_update = $('.change_date_for_product').val();

 $(document.body).on('change', '.change_date_for_product', function () {
        var product_id = $('.update_product_id').val();
        var date = $(this).val();
        if (date !== old_date_update) {
            old_date_update = date;
            $.ajax({
                url: basepath + "/ajax/get-product-available-slots",
                type: "POST",
                data: {product_id: product_id, date: date},
                success: function (data)
                {


                    var obj = JSON.parse(data);
                    if (obj.status == 200) {

                        $('.update_product_body').find('.booking_slots').html(obj.message.booking_slots);
                    } else {

                    }
                },
                error: function (e) {
                    console.log(e);
                }
            });
            return true;
        } else {

            return false;
        }

        console.log(date);
    });
    });
EOT_JS_CODE
);
?>

<script>

</script>