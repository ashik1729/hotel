<section class="banner text-center">
	<img class="banner-img" src="<?php

									use common\models\PackagesDate;
									use common\models\PackagesPrice;

									echo Yii::$app->request->baseUrl; ?>/uploads/product-banner/<?= $model->id; ?>/image/<?= $model->banner_image; ?>" alt="HCCA Packages Banner">
	<h3><?php echo $model->package_title; ?></h3>
	<h1 class="heading-main"><?php echo $model->destinations->title; ?></h1>
</section>


<section class="package_detail_section">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="d-flex flex-lg-row justify-content-lg-between flex-column">
					<div class="details-wrapper">
						<h1><?php echo $model->destinations->title; ?></h1>
						<?php echo $model->short_description_en; ?>
						<div class="detail-slider-area" style="padding-bottom: 40px;">


							<section id="demos">
								<div class="inner__slider owl-carousel owl-theme">

									<?php if ($model->gallery != '') {
										$images = explode(',', $model->gallery);
										$result_html = '';
										if ($images != NULL) {
											foreach ($images as $image) {

												$img = Yii::$app->request->baseUrl . '/uploads/products/' . base64_encode($model->id) . '/gallery/' . $image;
									?>
												<div class="item text-center">
													<img class="img-fluid d-block w-100" src="<?= $img; ?>" alt="<?= $visa->title; ?>">
												</div>

									<?php }
										}
									} ?>

								</div>
							</section>


						</div>

						<div class="package-more-info">
							<ul>
								<?php
								$packageDates = PackagesDate::find()->where(['package_date' => date('Y-m-d'), 'package_id' => $model->id])->one();
								if ($packageDates != NULL) {
									$getPrices = PackagesPrice::find()->where(['package_date_id' => $packageDates->id])->all();
									if ($getPrices != NULL) { ?>
										<?php foreach ($getPrices as $getPrice) { ?>

											<li>
												<i class="fas fa-dot-circle"></i> <?= $getPrice->min_person; ?> - <?= $getPrice->max_person; ?> People : AED <?= $getPrice->price; ?>
											</li>
								<?php
										}
									}
								}
								?>

							</ul>

							<a class="book-package-btn mt-4 d-block d-lg-none" href="book-packages-detail.html">Book package</a>

							<h4 class="mt-4">Overview</h4>
							<?php echo $model->overview;
							$expDatas = explode(',', $model->packaage_organize);
							?>
						</div>
						<div class="package-more-info">
							<h4>We Organize</h4>
							<ul>
								<?php if ($expDatas != NULL) { ?>
									<?php foreach ($expDatas as $expData) { ?>
										<li>
											<i class="fas fa-check-circle"></i> <?= $expData; ?>
										</li>
									<?php } ?>
								<?php } ?>

							</ul>
						</div>
						<!-- 
						<div class="package-more-info">
							<h4>Itinerary</h4>
							<div class="itinerary-box">
								<h5>124 and 125 floor for 1h30 – 299 AED per person </h5>
								<p>
									Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
									tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
									quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
									consequat.
								</p>
							</div>
						</div> -->

						<div class="package-more-info">
							<h4 class="reviews-heading">Customer Reviews</h4>
							<?php if ($packageReviews != NULL) {
								$totalReviews = 0;
							?>
								<?php foreach ($packageReviews as $packageReview) {

									$totalReviews += $packageReview->rating;
								} ?>
								<?php
								$averageReviews = round($totalReviews / count($packageReviews));
								?>

								<div class="reviews-area  mb-5">
									<div class="top-rating">
										<h1><?= $averageReviews; ?></h1>
										<div class="num-star">
											<div class="package__rating">
												<div class="package__rating-stars">
													<div class="rating">
														<div class="rating__body">
															<?php for ($i = 1; $i <= 5; $i++) { ?>
																<?php if ($i <= $averageReviews) { ?>
																	<div class="rating__star2 rating__star--active"></div>
																<?php } else { ?>
																	<div class="rating__star2 "></div>
																<?php } ?>
															<?php } ?>
														</div>
													</div>
												</div>
												<?php echo count($packageReviews); ?> Ratings
											</div>
										</div>
									</div>
									<?php
									foreach ($packageReviews as $packageReview) { ?>

										<div class="single-reviews ">
											<div class="d-flex">
												<div class="single-reviews-profile">
													<?php echo strtoupper(substr($packageReview->user->first_name, 0, 1)); ?>
												</div>
												<div class="single-reviews-head">
													<div class="name"><?= $packageReview->user->first_name; ?> <span><?= $packageReview->designation != "" ? $packageReview->designation : "Customer"; ?></span> </div>
													<div class="package__rating d-flex">
														<div class="package__rating-stars">
															<div class="rating">
																<div class="rating__body">
																	<?php for ($i = 1; $i <= 5; $i++) { ?>
																		<?php if ($i <= $packageReview->rating) { ?>
																			<div class="rating__star2 rating__star--active"></div>
																		<?php } else { ?>
																			<div class="rating__star2 "></div>
																		<?php } ?>
																	<?php } ?>
																</div>
															</div>
														</div>
														<div class="date"><?php echo date("M d, Y", strtotime($packageReview->created_at)); ?></div>
													</div>
												</div>
											</div>
											<p>
												<?php echo $packageReview->comment; ?>
											</p>
										</div>

									<?php } ?>
								</div>
							<?php } ?>

						</div>

						<div class="package-more-info">
							<h4>Terms and conditions</h4>
							<div class="itinerary-box">
								<?php echo $model->terms_condition; ?>
								<!-- <a class="wow fadeInUp" href="" style="visibility: visible; animation-name: fadeInUp;">
									<div class="goto-more">Read More <i class="fas fa-arrow-right" aria-hidden="true"></i></div>
								</a> -->
							</div>
						</div>

					</div>
					<div class="quick__link ">
						<div class="sticky-lg-top sticky-lg-top-90">
							<a class="book-package-btn mt-4 d-block d-lg-none" href="book-packages-detail.html">Book package</a>
							<div class="call-area">
								<h2>UAE CALL CENTER</h2>
								<ul>
									<li>
										<a href="tel:+97112345678">
											<img src="<?php echo Yii::$app->request->baseUrl; ?>/images/call-01.png" alt="HCCA Call Icon">
											+971 12 345 678
										</a>
									</li>
									<li>
										<a href="tel:+97112345678">
											<img src="<?php echo Yii::$app->request->baseUrl; ?>/images/call-02.png" alt="HCCA Call Icon">
											+971 12 345 678
										</a>
									</li>
									<li>
										<a href="mail:demo@demomail.com">
											<img src="<?php echo Yii::$app->request->baseUrl; ?>/images/call-03.png" alt="HCCA Call Icon">
											demo@demomail.com
										</a>
									</li>
								</ul>
							</div>
							<div class="other-link">
								<a class="other-page-link" href="<?php echo Yii::$app->request->baseUrl; ?>/visa">Get Travel Visa</a>
								<a class="other-page-link" href="<?php echo Yii::$app->request->baseUrl; ?>/flight-tickets">Book Flight Ticket</a>
								<a class="other-page-link" href="<?php echo Yii::$app->request->baseUrl; ?>/events">Organize Event</a>
								<?php
								if (!Yii::$app->user->isGuest) { ?>
									<a class="other-page-link" href="<?php echo Yii::$app->request->baseUrl; ?>/book-package/<?= $model->canonical_name;?>">Book The Package</a>
								<?php    } else { ?>
									<a class="other-page-link" href="" data-bs-toggle="modal" data-bs-target="#LoginEnquiry">Book The Package</a>
								<?php    }
								?>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>