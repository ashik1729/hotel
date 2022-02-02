<?php

use yii\bootstrap4\ActiveForm;

echo $this->render('account-menu',['active'=>'package-history']); ?>

		<section class="my-account-detials">
			<div class="container">
				<div class="row">
					<div class="col-12 d-flex flex-lg-row justify-content-between flex-column">
						<h1>Tour package history</h1>
						<div class="my-account-sort d-flex flex-sm-row justify-content-between align-items-sm-center flex-column">
							Tour package history
							<select required="" class="form-control select-form space-right">
								<option>Past 6 months</option>
								<option>Past 1 year</option>
							</select>
						</div>
					</div>
					<div class="col-12 tour-package-history-table">
						<table class="cart-table__table ">
							<tbody class="cart-table__body">
								<tr class="cart-table__row">
									<td class="cart-table__column cart-table__column--image">
										<div class="img-box">
											<img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/images/package/package-01.jpg">
										</div>	
									</td>
									<td class="cart-table__column cart-table__column--details">
										<p class="title">Burj Khalifa and Dubai Fountains</p>
										<p>Booking Date: Wendesday, 29 September, 2021</p>
										<div class="status">Status: <span>Confirmed</span></div>
									</td>
									<td class="cart-table__column cart-table__column--people">
										<p>7 People: 4.998$</p>
									</td>
									<td class="cart-table__column cart-table__column--action">
										<div>ORDER # XXX-XXXX-XXXX</div>
										<a class="leave" href="">Leave feedback</a> 
										<a class="cancel" href="">Cancel Booking</a>
									</td>
								</tr>
							</tbody>
						</table>
						<table class="cart-table__table ">
							<tbody class="cart-table__body">
								<tr class="cart-table__row">
									<td class="cart-table__column cart-table__column--image">
										<div class="img-box">
											<img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/images/package/package-01.jpg">
										</div>	
									</td>
									<td class="cart-table__column cart-table__column--details">
										<p class="title">Burj Khalifa and Dubai Fountains</p>
										<p>Booking Date: Wendesday, 29 September, 2021</p>
										<div class="status">Status: <span>Confirmed</span></div>
									</td>
									<td class="cart-table__column cart-table__column--people">
										<p>7 People: 4.998$</p>
									</td>
									<td class="cart-table__column cart-table__column--action">
										<div>ORDER # XXX-XXXX-XXXX</div>
										<a class="leave" href="">Leave feedback</a> 
										<a class="cancel" href="">Cancel Booking</a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</section>