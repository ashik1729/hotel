<?php

use common\models\CmsData;
use common\models\Settings;
use common\models\Variables;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$config = Settings::find()->where(['status' => 1])->one();
$facebook = Variables::find()->where(['key_name' => 'facebook', 'status' => 1])->one();
$twitter = Variables::find()->where(['key_name' => 'twitter', 'status' => 1])->one();
$instagramm = Variables::find()->where(['key_name' => 'instagramm', 'status' => 1])->one();
$linkedin = Variables::find()->where(['key_name' => 'linkedin', 'status' => 1])->one();
?>
<?php if ($model != NULL) { ?>
	<section class="banner text-center">
		<img class="banner-img" src="<?php echo Yii::$app->request->baseUrl; ?>/uploads/cms/<?= $model->id ?>/image/<?= $model->image; ?>" alt="<?= $model->title; ?>">

		<h3><?= $model->subtitle; ?></h3>
		<h1 class="heading-main"><?= $model->title; ?></h1>
	</section>
<?php } ?>

<section class="contact-section">
	<div class="container">
		<div class="row">

			<div class="col-12 ">
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
			<div class="col-12 text-center">
				<h2><?= $model->title; ?></h2>
				<?= $model->short_description; ?>
			</div>
			<div class="col-12">
				<div class="d-flex flex-lg-row justify-content-lg-between flex-column">
					<div class="left">
						<div class="contact-details">
							<?= $model->long_description; ?>
							<ul>
								<li>
									<div>
										<img src="<?php echo Yii::$app->request->baseUrl; ?>/images/map-pin.png" alt="HCCA Contact Icon">
									</div>
									<div>
										<a href="javascript:void(0)"><?= $config->address; ?></a>
									</div>
								</li>
								<li>
									<div>
										<img src="<?php echo Yii::$app->request->baseUrl; ?>/images/mail.png" alt="HCCA Contact Icon">
									</div>
									<div>
										<a href="">Mail Us <br><?= $config->email; ?></a>
									</div>
								</li>
								<li>
									<div>
										<img src="<?php echo Yii::$app->request->baseUrl; ?>/images/phone-call.png" alt="HCCA Contact Icon">
									</div>
									<div>
										<a href="">Call Us<br><?= $config->phone_number; ?></a>
									</div>
								</li>
							</ul>
							<div class="social-area ">
								<a href="<?= $facebook != NULL ? $facebook->key_value : ''; ?>"><i class="fab fa-facebook-f"></i></a>
								<a href="<?= $instagramm != NULL ? $instagramm->key_value : ''; ?>"><i class="fab fa-instagram"></i></a>
								<a href="<?= $twitter != NULL ? $twitter->key_value : ''; ?>"><i class="fab fa-twitter"></i></a>
								<a href="<?= $linkedin != NULL ? $linkedin->key_value : ''; ?>"><i class="fab fa-linkedin-in"></i></a>
							</div>
						</div>
					</div>
					<div class="right">
						<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'enableClientScript' => false]]); ?>
						<div class="form-group">
							<?= $form->field($enquiry, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Full Name', 'class' => 'form-control'])->label(false) ?>
						</div>
						<div class="form-group">
							<?= $form->field($enquiry, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email Address', 'class' => 'form-control'])->label(false) ?>
						</div>
						<div class="form-group">
							<?= $form->field($enquiry, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Contact Number', 'class' => 'form-control'])->label(false) ?>
						</div>
						<div class="form-group">
							<?= $form->field($enquiry, 'message')->textarea(['rows' => 6, 'class' => 'form-control-message', 'placeholder' => 'Message'])->label(false) ?>
						</div>
						<div class="form-group">
							<?= Html::submitButton('Send Message', ['class' => 'my-btn']) ?>
						</div>
						<?php ActiveForm::end(); ?>
					</div>
				</div>
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