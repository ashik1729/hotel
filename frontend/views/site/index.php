<?php

use common\models\Banner;
use common\models\CmsContent;
use common\models\CmsData;

$model = CmsContent::findOne(['page_id' => 'about-us']);
$home = CmsContent::findOne(['page_id' => 'home']);
$middleDataOne = CmsData::findOne(['page_id' => $model->id, 'can_name' => 'satified-customer']);
$middleDatatwo = CmsData::findOne(['page_id' => $model->id, 'can_name' => 'middledataone']);
$middleDatathree = CmsData::findOne(['page_id' => $model->id, 'can_name' => 'tours-are-completed']);
$middleDataOneFour = CmsData::findOne(['page_id' => $model->id, 'can_name' => 'travelling-experience']);
$middlevideo = CmsData::findOne(['page_id' => $model->id, 'can_name' => 'video-promotion']);
$homeService = CmsData::findOne(['page_id' => $home->id, 'can_name' => 'home-service-data']);
$homesliders = Banner::find()->where(['status' => 1])->all();
?>
<section class="slider">
	<!-- <ashik></ashik> -->
	<!-- <ali></ashik> -->
	<section id="demos">
		<div class="home-slider owl-carousel owl-theme">
			<?php if ($homesliders != NULL) { ?>
				<?php foreach ($homesliders as $homeslider) { ?>
					<div class="item">
						<img class="slider-image" src="<?php echo Yii::$app->request->baseUrl; ?>/uploads/marketing_banners/<?= $homeslider->id; ?>/android/<?= $homeslider->file_and; ?>" class="img-fluid d-block w-100" alt="...">
						<div class="carousel-caption">
							<div class="container">
								<div class="row">
									<div class="col-12 text-center">
										<h2 class="wow fadeInUp"><?= $homeslider->description_en; ?></h2>
										<h1 class="heading-main"><?= $homeslider->description_ar; ?></h1>
										<form action="<?php echo Yii::$app->request->baseUrl; ?>/packages" method="GET">
											<div class="slider-form form-group d-flex justify-content-between align-items-center">
												<input class="form-control" type="text" name="" placeholder="Search">
												<button class="search-btn" type="submit"><img src="<?php echo Yii::$app->request->baseUrl; ?>/images/search.png"></button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
	</section>
</section>
<section class="package__section">
	<div class="row no-padding">
		<div class="col-12 text-center">
			<h1 class="heading-main wow fadeInUp">Most Popular Packages</h1>
			<p class="wow fadeInUp">
				Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,
			</p>
		</div>
		<div class="col-12 no-padding">
			<div class="package__section-wrapper">
				<div class="package__section-itembox wow fadeInUp">
					<a href="">
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
					<a href="">
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
					<a href="">
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
					<a href="">
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
					<a href="">
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
					<a href="">
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
		</div>
		<div class="col-12 text-center">
			<a class="wow fadeInUp" href="">
				<div class="goto-more">View All Tours <i class="fas fa-arrow-right"></i></div>
			</a>
		</div>
	</div>
</section>





<section class="home__video">
	<div class="row no-padding">
		<div class="col-12">
			<div class="home__video-container">
				<!-- <img class="video-image" class="" src="images/video-bg.jpg" alt="HCCA Video Media"> -->
				<video class="video-image" autoplay loop>
					<source src="<?= Yii::$app->request->baseUrl; ?>/uploads/cms-data/<?= $middlevideo->id; ?>/file/<?= $middlevideo->file; ?>" type="video/mp4">
					<source src="<?= Yii::$app->request->baseUrl; ?>/uploads/cms-data/<?= $middlevideo->id; ?>/file/<?= $middlevideo->file; ?>" type="video/ogg">
					Your browser does not support the video tag.
				</video>
				<div class="content-panel text-center">
					<h5><?= $middlevideo->field_one; ?></h5>
					<h4 class="heading-main"><?= $middlevideo->field_two; ?></h4>
					<div class="video-button">
						<!-- <a href=""><img class="img-fluid" src="images/video-btn.png"></a> -->
					</div>
				</div>
			</div>
		</div>
	</div>
</section>



<section class="home__services">
	<div class="container">
		<div class="row">
			<div class="col-12 d-flex flex-lg-row justify-content-between flex-column">
				<div class="services-cnt-panel">
					<h1 class="heading-main wow fadeInUp">SERVICES</h1>
					<div class="item-panel d-flex flex-sm-row justify-content-between flex-column wow fadeInUp">
						<div class="icon-services icon01"></div>
						<div class="services-details">
							<a href="">
								<?php echo $homeService->field_one; ?>
							</a>
						</div>
					</div>
					<div class="item-panel d-flex flex-sm-row justify-content-between flex-column wow fadeInUp" data-wow-delay="300ms">
						<div class="icon-services icon02"></div>
						<div class="services-details">
							<a href="">
								<?php echo $homeService->field_two; ?>
							</a>
						</div>
					</div>
					<div class="item-panel d-flex flex-sm-row justify-content-between flex-column wow fadeInUp" data-wow-delay="600ms">
						<div class="icon-services icon03"></div>
						<div class="services-details">
							<a href="">
								<?php echo $homeService->field_three; ?>
							</a>
						</div>
					</div>
				</div>
				<div class="services-cnt-panel wow fadeInRight" data-wow-delay="400ms">
					<img class="img-fluid d-block w-100" src="<?php echo Yii::$app->request->baseUrl; ?>/uploads/cms-data/<?php echo $homeService->id; ?>/file/<?php echo $homeService->file; ?>" alt="HCCA Service Media">
				</div>
			</div>
		</div>
	</div>
</section>

<section class="why__choose">
	<div class="row no-padding">
		<div class="col-12 no-padding">
			<div class="d-flex flex-lg-row justify-content-between flex-column">
				<div class="why-img">
					<img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/images/why-hcca.jpg" alt="Why Choose HCCA Tours ">
				</div>
				<div class="why-cnt-panel">
					<h1 class="heading-main wow fadeInRight"><?php echo $home->short_description; ?> </h1>
					<div class="list-panel wow fadeInRight">
						<?php echo $home->long_description; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="home__counter about-counter">
	<div class="container">
		<div class="row">
			<div class="col-12 counter-col">
				<div class="wrapper">
					<div class="counter-box wow fadeInUp">
						<h2><span class="counter"><?= $middleDataOne != NULL ? $middleDataOne->field_one : ""; ?></span></h2>
						<h4><?= $middleDataOne->field_two; ?></h4>
					</div>
					<div class="counter-box wow fadeInUp" data-wow-delay="300ms">
						<h2><span class="counter"><?= $middleDatatwo != NULL ? $middleDatatwo->field_one : ""; ?></span></h2>
						<h4><?= $middleDatatwo->field_two; ?></h4>
					</div>
					<div class="counter-box wow fadeInUp" data-wow-delay="600ms">
						<h2><span class="counter"><?= $middleDatathree != NULL ? $middleDatathree->field_one : ""; ?></span></h2>
						<h4><?= $middleDatathree->field_two; ?></h4>
					</div>
					<div class="counter-box wow fadeInUp" data-wow-delay="900ms">
						<h2><span class="counter"><?= $middleDataOneFour != NULL ? $middleDataOneFour->field_one : ""; ?></span></h2>
						<h4><?= $middleDataOneFour->field_two; ?></h4>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="home__client">
	<div class="container position-relative">
		<div class="row">
			<div class="col-12 text-center">
				<h1 class="heading-main wow fadeInUp">What They’re Saying</h1>
			</div>
			<div class="col-lg-8 offset-lg-2 col-12 wow fadeInUp" data-wow-delay="300ms">
				<section id="demos">
					<div class="home-client__slider owl-carousel owl-theme">
						<div class="item text-center">
							<p>
								Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
								tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
								quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
								consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
								cillum dolore eu fugiat nulla pariatur.
							</p>
							<h3>
								Amy Johnson
							</h3>
							<h6>
								Traveler
							</h6>
							<div class="client-image">
								<img src="<?php echo Yii::$app->request->baseUrl; ?>/images/cilents-say/client-say-01.png" alt="HCCA Client">
							</div>
						</div>
						<div class="item text-center">
							<p>
								Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
								tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
								quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
								consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
								cillum dolore eu fugiat nulla pariatur.
							</p>
							<h3>
								Amy Johnson
							</h3>
							<h6>
								Traveler
							</h6>
							<div class="client-image">
								<img src="<?php echo Yii::$app->request->baseUrl; ?>/images/cilents-say/client-say-01.png" alt="HCCA Client">
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
</section>

<section class="home__who-we">
	<div class="container">
		<div class="row ">
			<div class="col-12 text-center">
				<h1 class="heading-main wow fadeInUp">Who We Are</h1>
			</div>
			<div class="col-lg-8 offset-lg-2 text-center wow fadeInUp" data-wow-delay="300ms">
				<?= $model->short_description; ?>
			</div>
			<div class="col-12">
				<div class="image-gallery d-flex justify-content-between align-items-center">
					<?php if ($model->gallery != '') { ?>
						<?php $images = explode(',', $model->gallery);
						$result_html = '';
						if ($images != NULL) {
							foreach ($images as $image) { ?>
								<div class="gallery-item wow fadeInUp">
									<img class="img-fluid d-block w-100" src="<?= Yii::$app->request->baseUrl; ?>/uploads/cms/<?= $model->id; ?>/gallery/<?= $image; ?>" alt="<?= $model->title; ?>">
								</div>
						<?php 	}
						}
						?>
				</div>
			<?php } ?>
			</div>
		</div>
	</div>
</section>



<section class="instagram-area">
	<div class="row">
		<div class="col-12 pb-4">
			<div class="col-12 text-center">
				<h1><i class="fab fa-instagram"></i> @ instagram</h1>
			</div>
			<div class="wrapper wow fadeInUp" data-wow-delay="300ms">
				<div class="insta-box">
					<a href="">
						<img class="img-fluid d-block w-100" src="<?php echo Yii::$app->request->baseUrl; ?>/images/instagram/instagram-01.jpg" alt="HCCA instagram">
					</a>
				</div>
				<div class="insta-box">
					<a href="">
						<img class="img-fluid d-block w-100" src="<?php echo Yii::$app->request->baseUrl; ?>/images/instagram/instagram-02.jpg" alt="HCCA instagram">
					</a>
				</div>
				<div class="insta-box">
					<a href="">
						<img class="img-fluid d-block w-100" src="<?php echo Yii::$app->request->baseUrl; ?>/images/instagram/instagram-03.jpg" alt="HCCA instagram">
					</a>
				</div>
				<div class="insta-box">
					<a href="">
						<img class="img-fluid d-block w-100" src="<?php echo Yii::$app->request->baseUrl; ?>/images/instagram/instagram-04.jpg" alt="HCCA instagram">
					</a>
				</div>
				<div class="insta-box">
					<a href="">
						<img class="img-fluid d-block w-100" src="<?php echo Yii::$app->request->baseUrl; ?>/images/instagram/instagram-05.jpg" alt="HCCA instagram">
					</a>
				</div>
				<div class="insta-box">
					<a href="">
						<img class="img-fluid d-block w-100" src="<?php echo Yii::$app->request->baseUrl; ?>/images/instagram/instagram-06.jpg" alt="HCCA instagram">
					</a>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Modal -->