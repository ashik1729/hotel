<?php

use common\models\PackagesDate;
use common\models\PackagesPrice;
use yii\bootstrap4\ActiveForm;

$packageDates = PackagesDate::find()->where(['package_date' => date('Y-m-d'), 'package_id' => $model->id])->all();

?>
<script>
	var date = "<?php echo $cart->date; ?>";
	var package_id = "<?php echo $model->id; ?>";
</script>
<section class="book-package-section">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<?php if (Yii::$app->session->hasFlash("success")) : ?>
					<div class="alert alert-success alert-dismissible">
						<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
						<span> <?= Yii::$app->session->getFlash("success") ?></span>
					</div>
				<?php endif; ?>
				<?php if (Yii::$app->session->hasFlash("error")) : ?>
					<div class="alert alert-danger alert-dismissible">
						<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
						<span> <?= Yii::$app->session->getFlash("error") ?></span>
					</div>
				<?php endif; ?>
				<div class="d-flex flex-lg-row justify-content-between flex-column">
					<div class="book-package-section-form">
						<h2>
							<?php echo $cart->product->package_title; ?>
						</h2>
						<?php $form = ActiveForm::begin(["id" => "adtocart", 'options' => ['enctype' => 'multipart/form-data', 'enableClientScript' => false, 'class' => 'row']]); ?>

						<div class="package__form-head d-flex flex-md-row justify-content-start flex-column">
							<div class="form-group">
								<h6>Date</h6>
								<p><?= date('Y-m-d', strtotime($cart->date)); ?></p>
								<p><?= date('D', strtotime($cart->date)); ?></p>
							</div>
							<div class="form-group">
								<h6>Adults</h6>
								<div class="quantity-counter">
									<a class="btn btn-quantity-down adult_down">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus">
											<line x1="5" y1="12" x2="19" y2="12"></line>
										</svg>
									</a>
									<input type="number" class="input-number__input form-control2 form-control-lg no_adult_data" name="Cart[no_adults]" min="1" max="100" step="1" value="1">
									<a class="btn btn-quantity-up adult_up">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
											<line x1="12" y1="5" x2="12" y2="19"></line>
											<line x1="5" y1="12" x2="19" y2="12"></line>
										</svg>
									</a>
								</div>
							</div>
							<div class="form-group">
								<h6>Kids (Age 0-2)</h6>
								<div class="quantity-counter">
									<a class="btn btn-quantity-down">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus">
											<line x1="5" y1="12" x2="19" y2="12"></line>
										</svg>
									</a>
									<input type="number" class="input-number__input form-control2 form-control-lg" name="Cart[no_children]" min="1" max="100" step="1" value="1">

									<a class="btn btn-quantity-up">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
											<line x1="12" y1="5" x2="12" y2="19"></line>
											<line x1="5" y1="12" x2="19" y2="12"></line>
										</svg>
									</a>
								</div>
							</div>
						</div>
						<h3>
							Traveler Details
						</h3>
						<div class="package__form-travel travel_data">
							<div class="travel-detail-item show-more-item item_data">
								<label>Adult <span class="row_count">1</span></label>
								<?= $form->field($booking_travellers, 'first_name[]')->textInput(['maxlength' => true, 'placeholder' => 'First Name', 'class' => 'form-control'])->label(false) ?>
								<?= $form->field($booking_travellers, 'last_name[]')->textInput(['maxlength' => true, 'placeholder' => 'Last Name', 'class' => 'form-control'])->label(false) ?>
								<div class="text-center">
									<a class="show-more-btn delete_data">
										<i class="fas fa-minus-circle "></i>
									</a>

								</div>
							</div>

						</div>
						<!-- <h3>
							Contact information
						</h3>
						<div class="package__form-travel2 ">
							<p>
								Your booking detials will be sent here.
							</p>
							<div class="travel-detail-item ">
								<div class="w-100 d-flex justify-content-between sm-space">
									<select class="phone-select ">
										<option>+971</option>
										<option>+91</option>
									</select>
									<input type="text" class="form-control number" name="" placeholder="Mobile Number">
								</div>
								<input type="mail" class="form-control" name="" placeholder="Email">
							</div>
						</div> -->
						<?php ActiveForm::end(); ?>
					</div>
					<div class="book-package-section-payment">
						<div class="cart-total">
							<h2>
								Cart totals
							</h2>
							<table class="cart-total-table__table ">
								<tbody class="cart-table__body">
									<tr class="cart-table__row">
										<th class="cart-table__column--th">Subtotal</th>
										<td class="cart-table__column--td subtotal"></td>
									</tr>
									<tr class="cart-table__row">
										<th class="cart-table__column--th">Total</th>
										<td class="cart-table__column--td total"></td>
									</tr>
								</tbody>
							</table>
							<!-- <form> -->
							<!-- <div class="form-group coupon-box">
									<input class="form-control" type="text" name="" placeholder="Coupon code if any">
									<button class="coupon-btn" type="submit">Apply</button>
								</div> -->

							<div class="form-group">
								<button type="submit" form="adtocart" for="adtocart" class="checkout-btn">Proceed To Payment</button>
							</div>
							<!-- </form> -->
						</div>
						<div class="call-area">
							<h2>UAE CALL CENTER</h2>
							<ul>
								<li>
									<a href="tel:+97112345678">
										<img src="images/call-01.png" alt="HCCA Call Icon">
										+971 12 345 678
									</a>
								</li>
								<li>
									<a href="tel:+97112345678">
										<img src="images/call-02.png" alt="HCCA Call Icon">
										+971 12 345 678
									</a>
								</li>
								<li>
									<a href="mail:demo@demomail.com">
										<img src="images/call-03.png" alt="HCCA Call Icon">
										demo@demomail.com
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>