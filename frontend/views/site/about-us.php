<?php

use common\models\CmsData;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<?php if ($model != NULL) { ?>
	<section class="banner text-center">
		<img class="banner-img" src="<?php echo Yii::$app->request->baseUrl; ?>/uploads/cms/<?= $model->id ?>/image/<?= $model->image; ?>" alt="<?= $model->title; ?>">
		<h1 class="heading-main"><?= $model->title; ?></h1>
	</section>
<?php } ?>

<?php
$middleDataOne = CmsData::findOne(['page_id' => $model->id, 'can_name' => 'satified-customer']);
$middleDatatwo = CmsData::findOne(['page_id' => $model->id, 'can_name' => 'middledataone']);
$middleDatathree = CmsData::findOne(['page_id' => $model->id, 'can_name' => 'tours-are-completed']);
$middleDataOneFour = CmsData::findOne(['page_id' => $model->id, 'can_name' => 'travelling-experience']);
$middlevideo = CmsData::findOne(['page_id' => $model->id, 'can_name' => 'video-promotion']);
?>
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

<section class="home__video">
	<div class="row no-padding">
		<div class="col-12">
			<div class="home__video-container">
				<!-- <img class="video-image" class="" src="images/video-bg.jpg" alt="HCCA Video Media"> -->
				<video  class="video-image"  autoplay loop>
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


<section class="instagram-area">
	<div class="row">
		<div class="col-12 pb-4">
			<div id="instafeed"></div>

			<div class="col-12 text-center">
				<h1><i class="fab fa-instagram"></i> @ instagram</h1>
			</div>
			<div class="wrapper wow fadeInUp" data-wow-delay="300ms">
				<div class="insta-box">
					<a href="">
						<img class="img-fluid d-block w-100" src="images/instagram/instagram-01.jpg" alt="HCCA instagram">
					</a>
				</div>
				<div class="insta-box">
					<a href="">
						<img class="img-fluid d-block w-100" src="images/instagram/instagram-02.jpg" alt="HCCA instagram">
					</a>
				</div>
				<div class="insta-box">
					<a href="">
						<img class="img-fluid d-block w-100" src="images/instagram/instagram-03.jpg" alt="HCCA instagram">
					</a>
				</div>
				<div class="insta-box">
					<a href="">
						<img class="img-fluid d-block w-100" src="images/instagram/instagram-04.jpg" alt="HCCA instagram">
					</a>
				</div>
				<div class="insta-box">
					<a href="">
						<img class="img-fluid d-block w-100" src="images/instagram/instagram-05.jpg" alt="HCCA instagram">
					</a>
				</div>
				<div class="insta-box">
					<a href="">
						<img class="img-fluid d-block w-100" src="images/instagram/instagram-06.jpg" alt="HCCA instagram">
					</a>
				</div>
			</div>
		</div>
	</div>
</section>