<section class="banner text-center">
			<img class="banner-img" src="<?php echo Yii::$app->request->baseUrl; ?>/images/packages-image.jpg" alt="HCCA Packages Banner">
			<h3>Our Packages</h3>
			<h1 class="heading-main">Tour packages listing</h1>
		</section>


		<section class="package__listing-page">
			<!-- <div class="container"> -->
				<div class="row no-padding">
					<div class="col-12 text-center ">
						<h1 class="heading-main">Tour Packages</h1>
					</div>
					<div class="col-12 no-padding mt-4 md-space-both">
						<div class="d-flex flex-lg-row justify-content-between flex-column">
							<div class="filter__search-area d-flex flex-column align-items-end">
								<div class="all-in-one">
									<div class="search-area">
										<h4>Search</h4>
										<div class="filter__search-form">
											<form class="d-flex justify-content-between align-items-center">
												<input class="form-control" type="text" name="" placeholder="Search">
												<button class="search-btn" type="button"><img src="<?php echo Yii::$app->request->baseUrl; ?>/images/search-white.png"></button>
											</form>
										</div>
									</div>
									<div class="other-filter-option d-none d-lg-block">
										<div class="destination-area">
											<h4>Destination</h4>
											<form class="d-flex justify-content-between align-items-center">
												<select class="form-control">
													<option selected >All Destinations</option>
													<option>Burj Khalifa and Dubai Fountains</option>
													<option>Desert Safari</option>
													<option>Burj Al Arab</option>
													<option>Dubai Marina</option>
													<option>Miracle Garden</option>
													<option>Ski Dubai</option>
												</select>
											</form>
										</div>

										<div class="category-area d-none d-lg-block">
											<h5>Select Category</h5>
											<form class="">
												<div class="form-group">
													<input type="checkbox" id="City Tour" name="City Tour">
													<label for="City Tour">City Tour</label>
												</div>
												<div class="form-group">
													<input type="checkbox" id="Adventure Tour" name="Adventure Tour">
													<label for="Adventure Tour">Adventure Tour</label>
												</div>
												<div class="form-group">
													<input type="checkbox" id="Group Tour" name="Group Tour">
													<label for="Group Tour">Group Tour</label>
												</div>
												<div class="form-group">
													<input type="checkbox" id="Village Tour" name="Village Tour">
													<label for="Village Tour">Village Tour</label>
												</div>
												<div class="form-group">
													<input type="checkbox" id="Beach Tour" name="Beach Tour">
													<label for="Beach Tour">Beach Tour</label>
												</div>
											</form>
										</div>
									</div>

									<div class="price-filter d-none d-lg-block">
										<h5>Filter By Price</h5>
									</div>

									<div class="d-flex d-none d-lg-block">
										<button class="filter-apply">Apply Filter</button>
									</div>

									<div class="md-screen-filter d-block d-lg-none">
										<a data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
											<img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/images/filter.png"> More Filter
										</a>
										<div class="offcanvas offcanvas-start package-filter" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
											<div class="offcanvas-header">
												<h5 class="offcanvas-title" id="offcanvasExampleLabel"></h5>
												<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
											</div>
											<div class="offcanvas-body">
												<div class="destination-area">
													<h4>Destination</h4>
													<form class="d-flex justify-content-between align-items-center">
														<select class="form-control">
															<option selected >All Destinations</option>
															<option>Burj Khalifa and Dubai Fountains</option>
															<option>Desert Safari</option>
															<option>Burj Al Arab</option>
															<option>Dubai Marina</option>
															<option>Miracle Garden</option>
															<option>Ski Dubai</option>
														</select>
													</form>
												</div>

												<div class="category-area">
													<h5>Select Category</h5>
													<form class="">
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
													</form>
												</div>

												<div class="price-filter">
													<h5>Filter By Price</h5>
												</div>

												<div class="">
													<button class="filter-apply">Apply Filter</button>
													<button class="filter-clear">Clear Filter</button>
												</div>

											</div>
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
								<div class="package__section-wrapper">
									<div class="package__section-itembox wow fadeInUp">
										<a href="<?php echo Yii::$app->request->baseUrl; ?>/package-details">
											<img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/images/package/package-01.jpg" alt="HCCA Package Burj Khalifa and Dubai Fountains">
											<div class="overlay_shadow">
											</div>
											<div class="pricing-area d-flex align-items-center justify-content-center flex-column">
												<div class="pricing-box">
													3 - 7 People : 4.998 $
												</div>
												<div class="pricing-box">
													8 – 12 People : 7.997 $
												</div>
												<div class="pricing-box">
													13 – 17 People : 4.998 $
												</div>
											</div>
											<div class="package__cnt-panel">
												<h2>124 and 125 floor <br>for 1h30</h2>
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
												Burj Khalifa and Dubai Fountains
											</div>
										</a>
									</div>
									<div class="package__section-itembox wow fadeInUp" data-wow-delay="300ms">
										<a href="<?php echo Yii::$app->request->baseUrl; ?>/package-details">
											<img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/images/package/package-02.jpg" alt="HCCA Package Desert Safari">
											<div class="overlay_shadow">
											</div>
											<div class="pricing-area d-flex align-items-center justify-content-center flex-column">
												<div class="pricing-box">
													3 - 7 People : 4.998 $
												</div>
												<div class="pricing-box">
													8 – 12 People : 7.997 $
												</div>
												<div class="pricing-box">
													13 – 17 People : 4.998 $
												</div>
											</div>
											<div class="package__cnt-panel">
												<h2>Red Dunes, Camels and <br>Barbecue 7h</h2>
												<div class="package__rating">
													<div class="package__rating-stars">
														<div class="rating">
															<div class="rating__body">
																<div class="rating__star2 rating__star--active"></div>
																<div class="rating__star2 rating__star--active"></div>
																<div class="rating__star2 rating__star--active"></div>
																<div class="rating__star2 rating__star--active"></div>
																<div class="rating__star2"></div>
															</div>
														</div>
													</div>
													38 Reviews
												</div>
											</div>
											<div class="item-title">
												Desert Safari
											</div>
										</a>
									</div>
									<div class="package__section-itembox wow fadeInUp" data-wow-delay="600ms">
										<a href="<?php echo Yii::$app->request->baseUrl; ?>/package-details">
											<img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/images/package/package-03.jpg" alt="HCCA Package Burj Khalifa and Dubai Fountains">
											<div class="overlay_shadow">
											</div>
											<div class="pricing-area d-flex align-items-center justify-content-center flex-column">
												<div class="pricing-box">
													3 - 7 People : 4.998 $
												</div>
												<div class="pricing-box">
													8 – 12 People : 7.997 $
												</div>
												<div class="pricing-box">
													13 – 17 People : 4.998 $
												</div>
											</div>
											<div class="package__cnt-panel">
												<h2>Speedboat in Dubai: Marina, <br>Atlantis, Palm and Burj Al…</h2>
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
												Burj Al Arab
											</div>
										</a>
									</div>
									<div class="package__section-itembox wow fadeInUp" data-wow-delay="400ms">
										<a href="<?php echo Yii::$app->request->baseUrl; ?>/package-details">
											<img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/images/package/package-04.jpg" alt="HCCA Package Burj Khalifa and Dubai Fountains">
											<div class="overlay_shadow">
											</div>
											<div class="pricing-area d-flex align-items-center justify-content-center flex-column">
												<div class="pricing-box">
													3 - 7 People : 4.998 $
												</div>
												<div class="pricing-box">
													8 – 12 People : 7.997 $
												</div>
												<div class="pricing-box">
													13 – 17 People : 4.998 $
												</div>
											</div>
											<div class="package__cnt-panel">
												<h2>Yacht Tour with Breakfast or<br>Barbecue 2-3 hours</h2>
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
												Dubai Marina
											</div>
										</a>
									</div>
									<div class="package__section-itembox wow fadeInUp" data-wow-delay="800ms">
										<a href="<?php echo Yii::$app->request->baseUrl; ?>/package-details">
											<img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/images/package/package-05.jpg" alt="HCCA Package Desert Safari">
											<div class="overlay_shadow">
											</div>
											<div class="pricing-area d-flex align-items-center justify-content-center flex-column">
												<div class="pricing-box">
													3 - 7 People : 4.998 $
												</div>
												<div class="pricing-box">
													8 – 12 People : 7.997 $
												</div>
												<div class="pricing-box">
													13 – 17 People : 4.998 $
												</div>
											</div>
											<div class="package__cnt-panel">
												<h2>4-hour Dubai Fauna and <br> Flora Tour</h2>
												<div class="package__rating">
													<div class="package__rating-stars">
														<div class="rating">
															<div class="rating__body">
																<div class="rating__star2 rating__star--active"></div>
																<div class="rating__star2 rating__star--active"></div>
																<div class="rating__star2 rating__star--active"></div>
																<div class="rating__star2 rating__star--active"></div>
																<div class="rating__star2"></div>
															</div>
														</div>
													</div>
													38 Reviews
												</div>
											</div>
											<div class="item-title">
												Miracle Garden
											</div>
										</a>
									</div>
									<div class="package__section-itembox wow fadeInUp" data-wow-delay="1200ms">
										<a href="<?php echo Yii::$app->request->baseUrl; ?>/package-details">
											<img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/images/package/package-06.jpg" alt="HCCA Package Burj Khalifa and Dubai Fountains">
											<div class="overlay_shadow">
											</div>
											<div class="pricing-area d-flex align-items-center justify-content-center flex-column">
												<div class="pricing-box">
													3 - 7 People : 4.998 $
												</div>
												<div class="pricing-box">
													8 – 12 People : 7.997 $
												</div>
												<div class="pricing-box">
													13 – 17 People : 4.998 $
												</div>
											</div>
											<div class="package__cnt-panel">
												<h2>2-Hour <br>Ski Session…</h2>
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
												Ski Dubai
											</div>
										</a>
									</div>
									<div class="package__section-itembox wow fadeInUp">
										<a href="<?php echo Yii::$app->request->baseUrl; ?>/package-details">
											<img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/images/package/package-01.jpg" alt="HCCA Package Burj Khalifa and Dubai Fountains">
											<div class="overlay_shadow">
											</div>
											<div class="pricing-area d-flex align-items-center justify-content-center flex-column">
												<div class="pricing-box">
													3 - 7 People : 4.998 $
												</div>
												<div class="pricing-box">
													8 – 12 People : 7.997 $
												</div>
												<div class="pricing-box">
													13 – 17 People : 4.998 $
												</div>
											</div>
											<div class="package__cnt-panel">
												<h2>124 and 125 floor <br>for 1h30</h2>
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
												Burj Khalifa and Dubai Fountains
											</div>
										</a>
									</div>
									<div class="package__section-itembox wow fadeInUp" data-wow-delay="300ms">
										<a href="<?php echo Yii::$app->request->baseUrl; ?>/package-details">
											<img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/images/package/package-02.jpg" alt="HCCA Package Desert Safari">
											<div class="overlay_shadow">
											</div>
											<div class="pricing-area d-flex align-items-center justify-content-center flex-column">
												<div class="pricing-box">
													3 - 7 People : 4.998 $
												</div>
												<div class="pricing-box">
													8 – 12 People : 7.997 $
												</div>
												<div class="pricing-box">
													13 – 17 People : 4.998 $
												</div>
											</div>
											<div class="package__cnt-panel">
												<h2>Red Dunes, Camels and <br>Barbecue 7h</h2>
												<div class="package__rating">
													<div class="package__rating-stars">
														<div class="rating">
															<div class="rating__body">
																<div class="rating__star2 rating__star--active"></div>
																<div class="rating__star2 rating__star--active"></div>
																<div class="rating__star2 rating__star--active"></div>
																<div class="rating__star2 rating__star--active"></div>
																<div class="rating__star2"></div>
															</div>
														</div>
													</div>
													38 Reviews
												</div>
											</div>
											<div class="item-title">
												Desert Safari
											</div>
										</a>
									</div>
									<div class="package__section-itembox wow fadeInUp" data-wow-delay="600ms">
										<a href="<?php echo Yii::$app->request->baseUrl; ?>/package-details">
											<img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/images/package/package-03.jpg" alt="HCCA Package Burj Khalifa and Dubai Fountains">
											<div class="overlay_shadow">
											</div>
											<div class="pricing-area d-flex align-items-center justify-content-center flex-column">
												<div class="pricing-box">
													3 - 7 People : 4.998 $
												</div>
												<div class="pricing-box">
													8 – 12 People : 7.997 $
												</div>
												<div class="pricing-box">
													13 – 17 People : 4.998 $
												</div>
											</div>
											<div class="package__cnt-panel">
												<h2>Speedboat in Dubai: Marina, <br>Atlantis, Palm and Burj Al…</h2>
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
												Burj Al Arab
											</div>
										</a>
									</div>
									<div class="package__section-itembox wow fadeInUp" data-wow-delay="400ms">
										<a href="<?php echo Yii::$app->request->baseUrl; ?>/package-details">
											<img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/images/package/package-04.jpg" alt="HCCA Package Burj Khalifa and Dubai Fountains">
											<div class="overlay_shadow">
											</div>
											<div class="pricing-area d-flex align-items-center justify-content-center flex-column">
												<div class="pricing-box">
													3 - 7 People : 4.998 $
												</div>
												<div class="pricing-box">
													8 – 12 People : 7.997 $
												</div>
												<div class="pricing-box">
													13 – 17 People : 4.998 $
												</div>
											</div>
											<div class="package__cnt-panel">
												<h2>Yacht Tour with Breakfast or<br>Barbecue 2-3 hours</h2>
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
												Dubai Marina
											</div>
										</a>
									</div>
									<div class="package__section-itembox wow fadeInUp" data-wow-delay="800ms">
										<a href="<?php echo Yii::$app->request->baseUrl; ?>/package-details">
											<img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/images/package/package-05.jpg" alt="HCCA Package Desert Safari">
											<div class="overlay_shadow">
											</div>
											<div class="pricing-area d-flex align-items-center justify-content-center flex-column">
												<div class="pricing-box">
													3 - 7 People : 4.998 $
												</div>
												<div class="pricing-box">
													8 – 12 People : 7.997 $
												</div>
												<div class="pricing-box">
													13 – 17 People : 4.998 $
												</div>
											</div>
											<div class="package__cnt-panel">
												<h2>4-hour Dubai Fauna and <br> Flora Tour</h2>
												<div class="package__rating">
													<div class="package__rating-stars">
														<div class="rating">
															<div class="rating__body">
																<div class="rating__star2 rating__star--active"></div>
																<div class="rating__star2 rating__star--active"></div>
																<div class="rating__star2 rating__star--active"></div>
																<div class="rating__star2 rating__star--active"></div>
																<div class="rating__star2"></div>
															</div>
														</div>
													</div>
													38 Reviews
												</div>
											</div>
											<div class="item-title">
												Miracle Garden
											</div>
										</a>
									</div>
									<div class="package__section-itembox wow fadeInUp" data-wow-delay="1200ms">
										<a href="<?php echo Yii::$app->request->baseUrl; ?>/package-details">
											<img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/images/package/package-06.jpg" alt="HCCA Package Burj Khalifa and Dubai Fountains">
											<div class="overlay_shadow">
											</div>
											<div class="pricing-area d-flex align-items-center justify-content-center flex-column">
												<div class="pricing-box">
													3 - 7 People : 4.998 $
												</div>
												<div class="pricing-box">
													8 – 12 People : 7.997 $
												</div>
												<div class="pricing-box">
													13 – 17 People : 4.998 $
												</div>
											</div>
											<div class="package__cnt-panel">
												<h2>2-Hour <br>Ski Session…</h2>
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
												Ski Dubai
											</div>
										</a>
									</div>
								</div>
								<div class="col-12 text-center">
									<a class="wow fadeInUp" href=""> 
										<div class="goto-more">Load More packages <i class="fas fa-arrow-right"></i></div>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			<!-- </div> -->
		</section>