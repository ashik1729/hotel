<section class="cart-section">
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
	</div>
	<div class="container">

		<div class="col-12">
			<h2>Cart</h1>
				<div class="d-flex flex-lg-row justify-content-between flex-column align-items-start">
					<div class="order-item-list">
						<div class="order-item">
							<?php if ($models != NULL) { ?>
								<?php foreach ($models as $model) {
									$imgPath = Yii::$app->request->baseUrl . '/uploads/products/' . base64_encode($model->product->id) . '/image/' . $model->product->image;
									$subtotal = $model->no_adults * $model->price;
									$total = ($model->no_adults * $model->price);

								?>
									<table class="cart-table__table ">
										<tbody class="cart-table__body">
											<tr class="cart-table__row">
												<td class="cart-table__column cart-table__column--image">
													<div class="img-box">
														<img class="img-fluid" src="<?= $imgPath;?>">
													</div>
												</td>
												<td class="cart-table__column cart-table__column--details">
													<p><?= $model->product->package_title; ?></p>
													<p>Booking Date:<?= date('D, d M, Y', strtotime($model->date)); ?></p>
													<div class="amount"><?php echo $model->no_adults; ?> Adults,<?php echo $model->no_children; ?> Children : AED <?php echo $model->no_adults * $model->price; ?></div>
												</td>
												<td class="cart-table__column cart-table__column--remove">
													<button class="remove"><i class="fas fa-trash-alt"></i></button>
												</td>
											</tr>
										</tbody>
									</table>
								<?php } ?>
							<?php } ?>
							
						</div>
					</div>
					<div class="cart-total">
						<h2>
							Cart totals
						</h2>
						<table class="cart-total-table__table ">
							<tbody class="cart-table__body">
								<tr class="cart-table__row">
									<th class="cart-table__column--th">Subtotal</th>
									<td class="cart-table__column--td">AED <?= $subtotal;?></td>
								</tr>
								<tr class="cart-table__row">
									<th class="cart-table__column--th">Total</th>
									<td class="cart-table__column--td">AED <?= $total;?></td>
								</tr>
							</tbody>
						</table>
						<!-- <form> -->
							<!-- <div class="form-group coupon-box">
								<input class="form-control" type="text" name="" placeholder="Coupon code if any">
								<button class="coupon-btn" type="submit">Apply</button>
							</div> -->

							<div class="form-group">
								<a href="<?= Yii::$app->request->baseUrl;?>/checkout" class="checkout-btn">Proceed To Checkout</a>
							</div>
						<!-- </form> -->
					</div>
				</div>
		</div>
	</div>
</section>