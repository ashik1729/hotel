<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\bootstrap4\Modal;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Orders */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$user = [];
$get_users = \common\models\User::find()->where(['status' => 10])->andWhere('user_type != 3')->all();
if ($get_users != NULL) {
    foreach ($get_users as $get_user) {
        $user[$get_user->id] = $get_user->first_name . ' ' . $get_user->last_name . '(' . $get_user->email . ')';
    }
}
?>

<?php
$get_franchises = \common\models\Franchise::find()->where(['status' => 10])->all();
if ($get_franchises != NULL) {
    foreach ($get_franchises as $get_franchise) {
        $franchise[$get_franchise->id] = $get_franchise->first_name . ' ' . $get_franchise->first_name . '(' . $get_franchise->country0->country_name . ')';
    }
}
$producturl = \yii\helpers\Url::to(['/ajax/get-item-list?store_id=' . $model->store]);
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
<div class="card-body orders-form">
    <?php $form = ActiveForm::begin(['id' => 'create_order', 'options' => ['class' => 'order_form']]); ?>

    <?= $form->errorSummary($model); ?>
    <div class="row">
        <div class="col-sm-2">

            <div class="form-group bmd-form-group">
                <?= $form->field($model, 'store')->dropDownList($franchise, ['prompt' => 'Choose A Franchise', 'class' => 'form-control franchise']); ?>
            </div>
        </div>
        <div class="col-sm-2">

            <div class="form-group bmd-form-group">
                <label class="control-label" for="productsservices-merchant_id">User
                </label>
                <?php
               /* echo Select2::widget([
                    'model' => $model,
                    'attribute' => 'user_id',
                    'data' => $user,
                    'disabled' => $disable,
                    'theme' => Select2::THEME_MATERIAL,
                    'options' => ['placeholder' => 'Select a  User.', 'class' => 'user_change'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'tokenSeparators' => [',', ' '],
                        'maximumInputLength' => 20
                    ],
                ]); */
                ?>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group bmd-form-group">
                <?= $form->field($usermodel, 'first_name')->textInput(['class' => 'form-control first_name user_info']) ?>

            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group bmd-form-group">
                <?= $form->field($usermodel, 'last_name')->textInput(['class' => 'form-control last_name user_info']) ?>

            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group bmd-form-group">
                <?= $form->field($usermodel, 'email')->textInput(['class' => 'form-control email user_info']) ?>

            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group bmd-form-group">
                <?= $form->field($usermodel, 'mobile_number')->textInput(['class' => 'form-control mobile_number user_info']) ?>

            </div>
        </div>

        <div class="col-sm-4">
            <div class="billing_info info_panel">
                <h6>Billing Information</h6>
                <?=
                $this->render('_add_address', [
                    'addressmodel' => $addressmodel,
                    'id' => "billing_address",
                    'field_class' => "bill_field",
                    'form' => $form
                ])
                ?>

            </div>
        </div>
        <div class="col-sm-4">
            <div class="shipping_info info_panel">
                <h6>Shipping Address Information</h6>
                <?=
                $this->render('_add_address', [
                    'addressmodel' => $addressmodel,
                    'id' => "shipping_address",
                    'field_class' => "ship_field",
                    'form' => $form
                ])
                ?>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="shipping_info info_panel">
                <h6>Payment Information</h6>
                <?=
                $this->render('_payment_info', [
                    'order' => $model,
                    'id' => "payment_info",
                    'form' => $form
                ])
                ?>
            </div>
        </div>
        <?php
        Pjax::begin(['id' => 'order_section']);
        $carts = [];
        if (Yii::$app->session->get('cart_session')) {
            $carts = common\models\Cart::find()->where(['session_id' => Yii::$app->session->get('cart_session')])->all();
        }
        ?>
        <div class="col-sm-12 ">
            <div class="card-body">
                <div class="card-header card-header-rose text-center m-0 p-1 font-weight-bold">
                    <h4 class="card-title">Order Items <?= count($carts) > 0 ? "(<span class='item_count'>" . count($carts) . "</span>)" : "(<span class='item_count'>0</span>)"; ?></h4>

                </div>
                <div class="material-datatables">

                    <div id="w4" class="grid-view">
                        <table class="table table-striped table-bordered"><thead>
                                <tr>
                                    <th>#</th>
                                    <th><a href="/caponcms/admin/order/orders/view?id=1&amp;sort=product_id" data-sort="product_id">Item</a></th>
                                    <th><a href="/caponcms/admin/order/orders/view?id=1&amp;sort=product_id" data-sort="product_id">Options</a></th>
                                    <th><a href="/caponcms/admin/order/orders/view?id=1&amp;sort=product_id" data-sort="product_id">Sku</a></th>
                                    <th><a href="/caponcms/admin/order/orders/view?id=1&amp;sort=product_id" data-sort="product_id">Item Type</a></th>
                                    <th><a href="/caponcms/admin/order/orders/view?id=1&amp;sort=merchant_id" data-sort="merchant_id">Merchant</a></th>
                                    <th><a href="/caponcms/admin/order/orders/view?id=1&amp;sort=quantity" data-sort="quantity">Quantity</a></th>
                                    <th><a href="/caponcms/admin/order/orders/view?id=1&amp;sort=date" data-sort="date">Deliery Date</a></th>
                                    <th><a href="/caponcms/admin/order/orders/view?id=1&amp;sort=booking_slot" data-sort="booking_slot">Time</a></th>
                                    <th><a href="/caponcms/admin/order/orders/view?id=1&amp;sort=amount" data-sort="amount">Unit Price</a></th>
                                    <th><a href="/caponcms/admin/order/orders/view?id=1&amp;sort=amount" data-sort="amount">Total</a></th>
                                    <th class="action-column">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($carts != NULL) { ?>
                                    <?php
                                    $i = 1;
                                    $subtotal = 0;
                                    foreach ($carts as $data) {
                                        ?>

                                        <tr data-key="1">
                                            <td><?= $i; ?></td>
                                            <td>
                                                <?php echo Html::a($data->product->product_name_en, Url::toRoute(['/products/products-services/view', 'id' => $data->product_id]), ['target' => '_blank', 'class' => 'font-weight-bolder']); ?>
                                                <a class="font-weight-bolder" href="/caponcms/admin/products/products-services/view?id=14" target="_blank">Laptop</a>
                                            </td>
                                            <td>
                                                <?php
                                                $get_options = explode(',', $data->options);
                                                $html = "";
                                                if ($get_options != NULL) {
                                                    foreach ($get_options as $get_option) {
                                                        $option_details = $data->getAttr($get_option);
                                                        if ($option_details != NULL) {
                                                            $html .= '<dl style="margin:0;padding:0"><dt><strong>' . $option_details->attributesValue->attributes0->name . ': </strong>
                                                                    <span style="margin:0;padding:0 0 0 9px">' . $option_details->attributesValue->value . '</span></dt></dl>';
                                                        }
                                                    }
                                                }
                                                if ($html != "") {

                                                    echo $html;
                                                } else {
                                                    echo "--";
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $data->product->sku ?></td>
                                            <td><?php echo $data->product->type == 1 ? "Product" : ($data->product->type == 2 ? "Shop Service" : ($data->product->type == 3 ? "Home Service" : "--")); ?></td>
                                            <td>
                                                <?php echo Html::a($data->product->merchant->first_name . ' ' . $data->product->merchant->last_name, Url::toRoute(['/users/merchant/view', 'id' => $data->product->merchant_id]), ['target' => '_blank', 'class' => 'font-weight-bolder']); ?>
                                            </td>
                                            <td><?= $data->quantity; ?></td>
                                            <td><?php
                                                if ($data->date) {
                                                    echo $data->date;
                                                } else {
                                                    echo '--';
                                                }
                                                ?>
                                            </td>
                                            <td><?php
                                                if ($data->booking_slot) {
                                                    echo $data->booking_slot;
                                                } else {
                                                    echo '--';
                                                }
                                                ?>
                                            </td>

                                            <td><?= Yii::$app->Currency->convert($data->product->price, 3); ?></td>
                                            <td><?= Yii::$app->Currency->convert(($data->product->price * $data->quantity), 3); ?></td>
                                            <td class="table-actions">
                                                <i cart_id="<?= $data->id; ?>" class="fa fa-edit load_item"></i>
                                                <i cart_id="<?= $data->id; ?>" class="fa fa-trash-o delete_item"></i>
                                            </td>
                                        </tr>

                                        <?php
                                        $subtotal += ($data->product->price * $data->quantity);
                                        $i++;
                                    }
                                    ?>
                                    <tr data-key="5">
                                        <td colspan="11" class="text-right"><span class="font-weight-bolder">Sub Total</span></td>
                                        <td colspan="2" class="text-left"><?php echo Yii::$app->Currency->convert($subtotal, 3); ?> </td>
                                    </tr>
                                    <?php
                                    $errors = [];
                                    Yii::$app->session->get('cart_session');
                                    $shipping_charge = 0;
                                    $cart_products = \common\models\Cart::find()->select('product_id')->where(['session_id' => Yii::$app->session->get('cart_session')])->asArray()->all();
                                    $product_lists = array_unique(array_column($cart_products, 'product_id'));
                                    if ($product_lists != NULL) {
                                        $get_products = common\models\ProductsServices::find()->where(['id' => $product_lists])->all();
                                        if ($get_products != NULL) {
                                            foreach ($get_products as $get_product) {
                                                $shipping_charge += $get_product->merchant->shipping_charge;
                                            }
                                        }
                                    }
                                    ?>
                                    <tr data-key="5">
                                        <td colspan="11" class="text-right"><span class="font-weight-bolder">Shipping Charge</span></td>
                                        <td colspan="2" class="text-left"><?php echo Yii::$app->Currency->convert($shipping_charge, 3); ?>  </td>
                                    </tr>
                                    <tr data-key="5">
                                        <td colspan="11" class="text-right"><span class="font-weight-bolder">Total</span></td>
                                        <td colspan="2" class="text-left"><?php echo Yii::$app->Currency->convert(($subtotal + $shipping_charge), 3); ?></td>
                                    </tr>
                                <?php } else { ?>
                                    <tr class="text-center" data-key="1">
                                        <td colspan="13">No Items Available</td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                    </div>
                    <table class="table table-striped table-bordered ">
                        <tbody>
                            <tr data-key="5">
                                <td colspan="7" class="text-right">
                                    <div class="card-body">
                                        <button type="button" class="btn btn-primary add_products" >Add products</button>
                                        <button class="btn btn-primary">Update Shipping</button>
                                        <button class="btn btn-primary">Add Coupons</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php Pjax::end()
        ?>

    </div>

    <div class="card-footer ml-auto mr-auto">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success save_order']) ?>
    </div>

    <?php ActiveForm::end(); ?>


    <?=
    $this->render('_order_product_edit', [
        'model' => $model,
        'id' => "order_product_edit"
    ])
    ?>
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="editAttributeValueModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAttributeValueModalTitle">Add Items</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php $fromaddProduct = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'form_product'], 'action' => 'add-to-cart?session_id=' . Yii::$app->session->get('cart_session')]); ?>
                <div class="modal-body">
                    <p class="attr_error"></p>
                    <div class="row ">


                        <div class="col-sm-12">
                            <div class="form-group bmd-form-group">
                                <?php
                                echo $fromaddProduct->field($cart_items, 'product_id')->widget(Select2::classname(), [
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

                                <?= $fromaddProduct->field($cart_items, 'id')->hiddenInput(['value' => $model->id])->label(FALSE) ?>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class=" col-sm-12">
                            <div class="booking_date "  style="display:none" >
                                <?= $fromaddProduct->field($cart_items, 'date')->textInput(['type' => 'date', 'id' => 'calenders', 'class' => 'change_date form-control']) ?>

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
                                <?= $fromaddProduct->field($cart_items, 'quantity')->textInput(['id' => 'input_update_attr_qty', 'value' => 1]) ?>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary save_update_attr">Add </button>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>


<script>
    var session_id = "<?= Yii::$app->session->get('cart_session'); ?>";
</script>


<?php
/* Create  Order */

$this->registerJs(<<< EOT_JS_CODE
        function userFilled() {
        var user = $('.user_change').val();
        var first_name = $('.first_name').val();
        var last_name = $('.last_name').val();
        var email = $('.email').val();
        var mobile_number = $('.mobile_number').val();

        if (user != "" || (first_name != "" && email != "" )) {
            return true;
        } else {
            return false;
        }
    }
        $(document.body).on('submit','.order_form', function (e) {
            var cart_items_count = $('.item_count').text();
        var check_user = userFilled();
        if (!check_user) {
            md.showNotification('bottom', 'center', 'User Information  before creating Order', '2');
           e.preventDefault();
        }
            if(cart_items_count == '0'){
                md.showNotification('bottom', 'center', "Atleast One Product need to add to cart before  creat Order", '2');
               e.preventDefault();
            }

    });
EOT_JS_CODE
);
?>
<?php
/* Delete The Cart Based On Session ID and Cart ID */

$this->registerJs(<<< EOT_JS_CODE




    $(document.body).on('click', '.delete_item', function () {
                                $('.loader-wrapp').show();

        var cart_id = $(this).attr('cart_id');
            $.ajax({
               url: basepath + "/order/orders/delete-cart?session_id="+session_id,
            type: "POST",
            data: {cart_id: cart_id},
                success: function (data)
                {


                    var obj = JSON.parse(data);
                    if (obj.status == 200) {
                        $('.loader-wrapp').hide();
                        md.showNotification('bottom', 'center', 'Item Deleted Successfully', '2');
                        $.pjax.reload({container: '#order_section', async: false, timeout: false});

                    } else {
                        md.showNotification('bottom', 'center', "Can't Able To Delete.Try again or contact administrator", '2');

                    }
                },
                error: function (e) {
                    console.log(e);
                }
            });
            return true;


        console.log(date);
    });
EOT_JS_CODE
);
?>
<?php
//Change Deleivery Date. get available booking Slots
$this->registerJs(<<< EOT_JS_CODE

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

EOT_JS_CODE
);
?>
<?php
//Update Cart Items
$this->registerJs(<<< EOT_JS_CODE


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
                    md.showNotification('bottom', 'center', 'Cart Updated', '3');
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

        //e.preventDefault();

    });
EOT_JS_CODE
);
?>
<?php
//Load Cart Items
$this->registerJs(<<< EOT_JS_CODE


 $(document.body).on("click", ".load_item", function (e) {
        $('.loader-wrapp').show();
        var cart_id = $(this).attr('cart_id');
        e.preventDefault();
        $.ajax({
            url: basepath + "/order/orders/load-cart-item",
            type: "POST",
            data: {cart_id: cart_id},
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
EOT_JS_CODE
);
?>
<?php
// Add Product To cart useing temp session id
$this->registerJs(<<< EOT_JS_CODE
    $('.form_product').on('beforeSubmit', function (e) {
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
                    $('#addProductModal').modal('hide');
                    $(".form_product")[0].reset();
                    $.pjax.reload({container: '#order_section', async: false, timeout: false});
                    $('.loader-wrapp').hide();
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                        md.showNotification('bottom', 'center', 'Item Added Successfully', '3');

                } else {
                    $('.loader-wrapp').hide();
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            },
            error: function () {

                    $('.loader-wrapp').hide();
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();

            }

        });

    }).on('submit', function (e) {

        e.preventDefault();

    });
EOT_JS_CODE
);
?>
<?php
//       serach Product Based on search key word
$this->registerJs(<<< EOT_JS_CODE


        $(document.body).on('change', '.change_product', function () {
        $('.loader-wrapp').show();
        var product_id = $(this).val();
        $.ajax({
            url: basepath + "/ajax/get-product-attributes",
            type: "POST",
            data: {product_id: product_id},
            success: function (data)
            {
               var obj = JSON.parse(data);
               if (obj.status == 200) {
                    if (obj.message.product_type == 2 || obj.message.product_type == 3) {
                        $('.booking_date').show();
                    } else {
                        $('.booking_date').hide();
                        $('.booking_slots').html("");
                    }
                    $('.product_attribute').html(obj.message.attributes);
                    $('.loader-wrapp').hide();
                } else {
                    $('.loader-wrapp').hide();
                }
            },
            error: function (e) {
                console.log(e);
                $('.loader-wrapp').hide();
            }
        });
    });
EOT_JS_CODE
);
?>
<?php
//Getting Booking Timeslot based on given date
$this->registerJs(<<< EOT_JS_CODE
    var old_date = $('.change_date').val();
    $(document.body).on('change', '.change_date', function () {

        var product_id = $('.change_product').val();
        var date = $(this).val();
        if (date !== old_date) {
                                            $('.loader-wrapp').show();

            old_date = date;
            $.ajax({
                url: basepath + "/ajax/get-product-available-slots",
                type: "POST",
                data: {product_id: product_id, date: date},

                success: function (data)
                {


                    var obj = JSON.parse(data);
                    if (obj.status == 200) {

                        $('.booking_slots').html(obj.message.booking_slots);
                                    $('.loader-wrapp').hide();

                    } else {
                                    $('.loader-wrapp').hide();

                    }
                },
                error: function (e) {
                    console.log(e);
                                            $('.loader-wrapp').hide();

                }
            });
            return true;
        } else {

            return false;
        }

        console.log(date);

    });

EOT_JS_CODE
);
?>
<?php
//Get User info based on change user dropdown
$this->registerJs(<<< EOT_JS_CODE
    $(document).ready(function () {
$(document.body).on('change', '.user_change', function () {
        var user_id = $(this).val();

        $.ajax({
            url: basepath + "/order/orders/get-user-info",
            type: "POST",
            data: {user_id: user_id},
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj.status == 200) {
                    if (obj.message.data == undefined || obj.message.data == null || obj.message.data.length == 0) {
                        $('.user_info').removeAttr('disabled');
 $('.first_name').val("");
                        $('.last_name').val("");
                        $('.email').val("");
                        $('.mobile_number').val("");
                    } else {
                        $('.first_name').val(obj.message.data.first_name);
                        $('.last_name').val(obj.message.data.last_name);
                        $('.email').val(obj.message.data.email);
                        $('.mobile_number').val(obj.message.data.mobile_number);
                        $('.user_info').attr('disabled', true);
                    }
                } else {
                    $('.user_info').removeAttr('disabled');

                }
            },
            error: function () {

                md.showNotification('bottom', 'center', 'Something went wrong', '2');

            }

        });
    });
      //Checking user field already filled or not

    $(document.body).on('click', '.add_products', function () {
        var check_user = storeFilled();
        if (check_user) {
            $("#addProductModal").modal("show");
        } else {
            md.showNotification('bottom', 'center', 'Choose a franchise before adding products', '2');
            return false;
        }
    });
            function storeFilled() {
        var franchise = $('.franchise').val();
        var first_name = $('.first_name').val();
        var last_name = $('.last_name').val();
        var email = $('.email').val();
        var mobile_number = $('.mobile_number').val();

        if (franchise != "" ) {
            return true;
        } else {
            return false;
        }
    }

    });
EOT_JS_CODE
);
?>