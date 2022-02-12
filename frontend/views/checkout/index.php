<section class="checkout-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>Checkout</h1>
                    <?php

                    use yii\bootstrap4\ActiveForm;
                    use yii\helpers\ArrayHelper;

                    $form = ActiveForm::begin(["id" => "adtocart", 'options' => ['enctype' => 'multipart/form-data', 'enableClientScript' => false, 'class' => 'row ']]); ?>

                    <div class=" d-flex flex-lg-row justify-content-between flex-column flex-column-reverse align-items-start">

                        <div class="checkout-list">
                            <h3>Review your Booking</h3>
                            <table class="checkout-table__table ">
                                <tbody class="checkout-table__body">
                                    <?php if ($models != NULL) { ?>
                                        <?php foreach ($models as $model) {
                                            $imgPath = Yii::$app->request->baseUrl . '/uploads/products/' . base64_encode($model->product->id) . '/image/' . $model->product->image;
                                            $subtotal = $model->no_adults * $model->price;
                                            $total = ($model->no_adults * $model->price);

                                        ?>

                                            <tr class="checkout-table__row">
                                                <td class="checkout-table__column checkout-table__column--image">
                                                    <div class="img-box">
                                                        <img class="img-fluid" src="<?= $imgPath; ?>">

                                                    </div>
                                                </td>
                                                <td class="checkout-table__column checkout-table__column--details">
                                                    <p><?= $model->product->package_title; ?></p>
                                                    <p>Booking Date:<?= date('D, d M, Y', strtotime($model->date)); ?></p>
                                                    <div class="amount"><?php echo $model->no_adults; ?> Adults,<?php echo $model->no_children; ?> Children : AED <?php echo $model->no_adults * $model->price; ?></div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>

                                    <tr class="checkout-table__row">
                                        <th class=" checkout-table__total">
                                            Subtotal
                                        </th>
                                        <td class=" checkout-table__amount">
                                            AED <?php echo $subtotal; ?>
                                        </td>
                                    </tr>
                                    <tr class="checkout-table__row">
                                        <th class="checkout-table__column checkout-table__total">
                                            Total
                                        </th>
                                        <td class="checkout-table__column checkout-table__amount">
                                            AED <?php echo $total; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="form-group">
                                <label class="radio-box">Cash On Delivery
                                    <?php // echo $form->field($order, 'payment_method')->radio(['value' => 1, 'uncheck' => null])->label(false) ?>

                                    <input type="radio" value="1" checked name="Orders[payment_method]">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="radio-box">Credit / Debit Card
                                    <input type="radio" value="2" name="Orders[payment_method]">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="d-flex flex-sm-row-reverse ">
                                <button type="submit" class="payment-btn ">
                                    Proceed To Payment
                                </button>
                            </div>
                        </div>
                        <div class="checkout-details">
                            <!-- <div class="d-flex justify-content-between align-items-center">
                                <h3>Billing details</h3>
                                <div class="returning">
                                    Returning customer?
                                    <a href="">Click Here To Login</a>
                                </div>
                            </div> -->
                            <form class="billing-form">
                                <div class="form-group">
                                    <?= $form->field($userAddress, 'first_name')->textInput(['maxlength' => true, 'placeholder' => 'Full Name', 'class' => 'form-control'])->label(false) ?>

                                </div>
                                <div class="form-group">
                                    <?= $form->field($userAddress, 'country')->dropDownList(ArrayHelper::map(\common\models\Country::find()->all(), 'id', 'country_name'), ['prompt' => 'Choose Country', 'class' => 'form-control select_country select_form'])->label(FALSE); ?>


                                </div>
                                <div class="form-group">
                                    <?= $form->field($userAddress, 'phone_number')->textInput(['maxlength' => true, 'placeholder' => 'Phone Number', 'class' => 'form-control'])->label(false) ?>

                                </div>
                                <div class="form-group">
                                    <?= $form->field($userAddress, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email Address', 'class' => 'form-control'])->label(false) ?>

                                </div>
                                <div class="form-group">
                                    <label>Additional Information</label>

                                    <?= $form->field($order, 'customer_comment')->textarea(['rows' => 6, 'placeholder' => "Order notes(optinal)"]) ?>

                                </div>
                            </form>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</section>