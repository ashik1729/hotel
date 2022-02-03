		<?php

		use common\models\PackagesDate;
		use common\models\PackagesPrice;

		if ($model != NULL) { ?>
			<section class="banner text-center">
				<img class="banner-img" src="<?php echo Yii::$app->request->baseUrl; ?>/uploads/cms/<?= $model->id ?>/image/<?= $model->image; ?>" alt="<?= $model->title; ?>">
				<h3 class=""><?= $model->title; ?></h3>
				<h1 class="heading-main"><?= $model->subtitle; ?></h1>
			</section>
		<?php } ?>
		<section class="package__listing-page">
			<!-- <div class="container"> -->
			<div class="row no-padding">
				<div class="col-12 text-center ">
					<h1 class="heading-main"><?= $model->title; ?></h1>
				</div>
				<div class="col-12 no-padding mt-4 md-space-both">
					<div class="d-flex flex-lg-row justify-content-between flex-column">
						<div class="filter__search-area d-flex flex-column align-items-end">
							<div class="all-in-one">

								<div class="search-area">
									<h4>Search</h4>
									<div class="filter__search-form">
										<form method="GET" class="d-flex justify-content-between align-items-center">
											<input class="form-control" type="text" name="search_key" placeholder="Search">
											<button class="search-btn" type="submit"><img src="<?php echo Yii::$app->request->baseUrl; ?>/images/search-white.png"></button>
										</form>
									</div>
								</div>
								<form class="" method="GET">
									<div class="other-filter-option d-none d-lg-block">
										<div class="destination-area">
											<h4>Destination</h4>

											<select name="destination" class="form-control">
												<option value="" selected>All Destinations</option>
												<?php if ($destinations != NULL) { ?>
													<?php foreach ($destinations as $destination) { ?>
														<option <?= (isset($_GET['destination']) && $_GET['destination'] == $destination->id) ? "selected" : ""; ?> value="<?php echo $destination->id; ?>"><?php echo $destination->title; ?></option>
													<?php } ?>
												<?php } ?>
											</select>

										</div>

										<div class="category-area d-none d-lg-block">
											<h5>Select Category</h5>
											<?php if ($cateory != NULL) { ?>
												<?php foreach ($cateory as $cat) { ?>
													<div class="form-group">
														<input <?= $_GET['category'] != NULL ? in_array($cat->id, $_GET['category']) ? "checked" : "" : "" ?> type="checkbox" id="category-<?= $cat->id; ?>" value="<?= $cat->id; ?>" name="category[]">
														<label for="category-<?= $cat->id; ?>"><?= $cat->category_name; ?></label>
													</div>
												<?php } ?>
											<?php } ?>
										</div>
									</div>

									<!-- <div class="price-filter d-none d-lg-block">
									<h5>Filter By Price</h5>
								</div> -->

									<div class="d-flex d-none d-lg-block">
										<button type="submit" class="filter-apply">Apply Filter</button>
									</div>
								</form>

								<div class="md-screen-filter d-block d-lg-none">
									<a data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
										<img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/images/filter.png"> More Filter
									</a>
									<div class="offcanvas offcanvas-start package-filter" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
										<div class="offcanvas-header">
											<h5 class="offcanvas-title" id="offcanvasExampleLabel"></h5>
											<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
										</div>
										<form class="">
											<div class="offcanvas-body">
												<div class="destination-area">
													<h4>Destination</h4>
													<form class="d-flex justify-content-between align-items-center">
														<select class="form-control" name="destination">
															<option value="" selected>All Destinations</option>
															<?php if ($destinations != NULL) { ?>
																<?php foreach ($destinations as $destination) { ?>
																	<option <?= (isset($_GET['destination']) && $_GET['destination'] == $destination->id) ? "selected" : ""; ?> value="<?php echo $destination->id; ?>"><?php echo $destination->title; ?></option>
																<?php } ?>
															<?php } ?>

														</select>
													</form>
												</div>

												<div class="category-area">
													<h5>Select Category</h5>

													<div class="form-group">
														<input type="checkbox" id="City Tour2" name="City Tour2">
														<label for="City Tour2">City Tour</label>
													</div>
													<div class="form-group">
														<input type="checkbox" id="Adventure Tour2" name="Adventure Tour2">
														<label for="Adventure Tour2">Adventure Tour</label>
													</div>
													<div class="form-group">
														<input type="checkbox" id="Group Tour2" name="Group Tour2">
														<label for="Group Tour2">Group Tour</label>
													</div>
													<div class="form-group">
														<input type="checkbox" id="Village Tour2" name="Village Tour2">
														<label for="Village Tour2">Village Tour</label>
													</div>
													<div class="form-group">
														<input type="checkbox" id="Beach Tour3" name="Beach Tour3">
														<label for="Beach Tour3">Beach Tour</label>
													</div>

												</div>

												<div class="price-filter">
													<h5>Filter By Price</h5>
												</div>

												<div class="">
													<button type="submit" class="filter-apply">Apply Filter</button>
													<a href="<?php echo Yii::$app->request->baseUrl; ?>/packages" class="filter-clear">Clear Filter</a>
												</div>

											</div>
										</form>
									</div>
								</div>

							</div>
						</div>
						<div class="package__listing-area">
							<div class="filter-sorting-result-area">
								<div class="d-flex flex-lg-row justify-content-between">
									<div class="filter-result-area">
										<div class="filter-result">
											<span>All Destinations</span>
											<button class="filter-close"><i class="fas fa-times" aria-hidden="true"></i></button>
										</div>
										<div class="filter-result">
											<span>AED20 to AED1000</span>
											<button class="filter-close"><i class="fas fa-times" aria-hidden="true"></i></button>
										</div>
										<a class="clear-filter" href=""><i class="fas fa-times" aria-hidden="true"></i> Clear all filters</a>
									</div>
								</div>
							</div>
							<?php if (isset($_GET['search_key']) && $_GET['search_key'] != "") { ?>
								<div class="col-xs-12">
									<h6>Search Result Based On "<?php echo $_GET['search_key']; ?>"</h6>
								</div>
								<br />
							<?php } ?>
							<div class="package__section-wrapper">

								<?php if ($packages != NULL) { ?>
									<?php foreach ($packages as $package) {
										$imgPath = Yii::$app->request->baseUrl . '/uploads/products/' . base64_encode($package->id) . '/image/' . $package->image;

									?>
										<div class="package__section-itembox wow fadeInUp">

											<a href="<?= Yii::$app->request->baseUrl; ?>/package-details/<?php echo $package->canonical_name; ?>">
												<img class="img-fluid" src="<?php echo $imgPath; ?>" alt="HCCA Package Burj Khalifa and Dubai Fountains">
												<div class="overlay_shadow">
												</div>
												<div class="pricing-area d-flex align-items-center justify-content-center flex-column">
												<?php $packageDates = PackagesDate::find()->where(['package_date' => date('Y-m-d'),'package_id'=>$package->id])->one();
													if ($packageDates != NULL) {
														$getPrices = PackagesPrice::find()->where(['package_date_id' => $packageDates->id])->all();
														if ($getPrices != NULL) { ?>
															<?php foreach ($getPrices as $getPrice) { ?>
																<div class="pricing-box">
																	<?= $getPrice->min_person; ?> - <?= $getPrice->max_person; ?> People : AED <?= $getPrice->price; ?>
																</div>
													<?php
															}
														}
													}
													?>
												</div>
												<div class="package__cnt-panel">
													<h2><?= $package->package_title; ?></h2>
													<div class="package__rating">
														<div class="package__rating-stars">
															<div class="rating">
																<div class="rating__body">
																	<div class="rating__star2 rating__star--active"></div>
																	<div class="rating__star2 rating__star--active"></div>
																	<div class="rating__star2 rating__star--active"></div>
																	<div class="rating__star2 rating__star--active"></div>
																	<div class="rating__star2 rating__star--active"></div>
																</div>
															</div>
														</div>
														38 Reviews
													</div>
												</div>
												<div class="item-title">
													<?= $package->destinations->title; ?>
												</div>
											</a>
										</div>
									<?php } ?>
								<?php } else {
									echo "<h5>No Result Found Based On Your Search Criteria</h5>";
								} ?>

							</div>
							<!-- <div class="col-12 text-center">
								<a class="wow fadeInUp" href="">
									<div class="goto-more">Load More packages <i class="fas fa-arrow-right"></i></div>
								</a>
							</div> -->
						</div>
					</div>
				</div>
			</div>
			<!-- </div> -->
		</section>