<?php

use common\models\CmsData;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>

<section class="accommodation-gallery gallery-page">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<h1 class="heading-main wow fadeInUp">Gallery</h1>

				<div class="accommodation-gallery-wrapper">
					<?php if ($model != NULL) { ?>
						<?php
						if ($model->gallery != '') {
							$images = explode(',', $model->gallery);
							if ($images != NULL) {
								foreach ($images as $image) {
									$result_url = Yii::$app->request->baseUrl . '/uploads/accomodation/' . $model->id . '/gallery/' . $image;
						?>
									<div class="gallery-item wow fadeInUp" data-wow-delay="300ms">
										<a rel="lightbox-demo" href="<?= $result_url; ?>">
											<img src="<?= $result_url; ?>">
										</a>
									</div>
					<?php
								}
							}
						}
					} ?>

				</div>
			</div>
		</div>
	</div>
</section>

<section class="accommodation-gallery gallery-page">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<h1 class="heading-main wow fadeInUp">Gallery</h1>
						<div class="accommodation-gallery-wrapper">
							<div class="gallery-item wow fadeInUp" data-wow-delay="300ms">
								<a rel="lightbox-demo" href="<?= Yii::$app->request->baseUrl ;?>/images/accommodation-gallery/accommodation-gallery-01.jpg" >
									<img src="<?= Yii::$app->request->baseUrl ;?>/images/accommodation-gallery/accommodation-gallery-01.jpg">
								</a>
							</div>
							<div class="gallery-item wow fadeInUp" data-wow-delay="400ms">
								<a rel="lightbox-demo" href="<?= Yii::$app->request->baseUrl ;?>/images/accommodation-gallery/accommodation-gallery-02.jpg" >
									<img src="<?= Yii::$app->request->baseUrl ;?>/images/accommodation-gallery/accommodation-gallery-02.jpg">
								</a>
							</div>
							<div class="gallery-item wow fadeInUp" data-wow-delay="500ms">
								<a rel="lightbox-demo" href="<?= Yii::$app->request->baseUrl ;?>/images/accommodation-gallery/accommodation-gallery-03.jpg" >
									<img src="<?= Yii::$app->request->baseUrl ;?>/images/accommodation-gallery/accommodation-gallery-03.jpg">
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>