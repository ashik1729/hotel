<?php

use common\models\CmsData;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>

<section class="accommodation-page">
	<div class="container">
		<div class="row">

			<div class="col-12">
				<h1 class="heading-main text-center wow fadeInUp" data-wow-delay="300ms"><?= $model->title; ?></h1>
				<div class="col-lg-8 offset-lg-2 col-12">
					<div class="accommodation-form-box wow fadeInUp" data-wow-delay="300ms">
						<h3>Enter what you need</h3>
						<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'enableClientScript' => false, 'class' => 'row']]); ?>
						<div class="col-md-6  col-12">
							<?= $form->field($accommodation, 'destination')->textInput(['maxlength' => true, 'placeholder' => 'Destination', 'class' => 'form-control'])->label(false) ?>
						</div>
						<div class="col-md-6  col-12">
							<?= $form->field($accommodation, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Full Name', 'class' => 'form-control'])->label(false) ?>
						</div>
						<div class="col-md-6  col-12">
							<?= $form->field($accommodation, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email Address', 'class' => 'form-control'])->label(false) ?>
						</div>
						<div class="col-md-6  col-12">
							<?= $form->field($accommodation, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Phone Number', 'class' => 'form-control'])->label(false) ?>
						</div>
						<div class="col-md-6 col-12">
							<label>Check-in date</label>
							<?= $form->field($accommodation, 'checkin_date')->textInput(['maxlength' => true, 'type' => 'date', 'min' => date('Y-m-d'), 'max' => date('Y-m-d', strtotime('+5 years')), 'placeholder' => 'Check In Date', 'class' => 'form-control  date-area date-picker'])->label(false) ?>
						</div>
						<div class="col-md-6 col-12">
							<label>Check-out date</label>
							<?= $form->field($accommodation, 'checkout_date')->textInput(['maxlength' => true, 'type' => 'date', 'min' => date('Y-m-d'), 'max' => date('Y-m-d', strtotime('+5 years')), 'placeholder' => 'Check In Date', 'class' => 'form-control  date-area date-picker'])->label(false) ?>
						</div>
						<div class="col-md-6 col-12">
							<?= $form->field($accommodation, 'no_adult')->dropDownList(
								[
									'1' => '1',
									'2' => '2',
									'3' => '3',
									'4' => '4',
									'5' => '5',
									'6' => '6',
									'7' => '7',
									'8' => '8',
								],
								['prompt' => 'No. of adults', 'class' => 'form-control select select-form']
							)->label(false) ?>
						</div>
						<div class="col-md-6 col-12">
							<?= $form->field($accommodation, 'no_children')->dropDownList(
								[
									'1' => '1',
									'2' => '2',
									'3' => '3',
									'4' => '4',
									'5' => '5',
									'6' => '6',
									'7' => '7',
									'8' => '8',
								],
								['prompt' => 'No. of Children', 'class' => 'form-control select select-form']
							)->label(false) ?>
						</div>
						<div class="col-md-6 col-12">

							<?= $form->field($accommodation, 'no_room')->dropDownList(
								[
									'1' => '1',
									'2' => '2',
									'3' => '3',
									'4' => '4',
									'5' => '5',
									'6' => '6',
									'7' => '7',
									'8' => '8',
								],
								['prompt' => 'No. of Room', 'class' => 'form-control select select-form']
							)->label(false) ?>
						</div>
						<div class="col-md-6 col-12">
							<?php
							echo $form->field($accommodation, 'accomodation')->dropDownList(ArrayHelper::map(\common\models\Accomodation::find()->all(), 'id', 'title'), ['class' => 'form-control', 'prompt' => 'Choose Accomodation Type'])->label(false);
							?>
						</div>
						<div class="col-md-6 col-12">
							<?= $form->field($accommodation, 'purpose')->radio(['label' => 'I am traveling for tourism', 'value' => 1, 'uncheck' => null]) ?>
						</div>
						<div class="col-md-6 col-12">
							<?= $form->field($accommodation, 'purpose')->radio(['label' => 'I am traveling for work', 'value' => 2, 'uncheck' => null]) ?>
						</div>
						<div class="col-12">
							<?= Html::submitButton('Send', ['class' => 'my-btn']) ?>
						</div>
						<?php ActiveForm::end(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="accommodation-provide">
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
			<div class="col-12">
				<div class="d-flex flex-lg-row justify-content-between flex-column">
					<div class="accommodation-image wow fadeInLeft" data-wow-delay="300ms">
						<img class="banner-img" src="<?php echo Yii::$app->request->baseUrl; ?>/uploads/cms/<?= $model->id ?>/image/<?= $model->image; ?>" alt="<?= $model->title; ?>">

					</div>
					<div class="accommodation-detail">
						<h3 class="wow fadeInUp" data-wow-delay="600ms"><?= $model->subtitle; ?></h3>
						<?= $model->short_description; ?>

					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
$additionalData = CmsData::findOne(['page_id' => $model->id, 'can_name' => 'accomodation-in-touch']);
?>
<section class="accommodation-enquiry">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="d-flex flex-lg-row justify-content-between flex-column">
					<div class="accommodation-enquiry-form">
						<h1 class="heading-main wow fadeInUp">
							Get In Touch<br>With Us!
						</h1>
						<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'enableClientScript' => false, 'class' => "wow fadeInUp"]]); ?>
						<div class="col-12">
							<div class="form-group">
								<?= $form->field($enquiry, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Full Name', 'class' => 'form-control'])->label(false) ?>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<?= $form->field($enquiry, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email Address', 'class' => 'form-control'])->label(false) ?>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<?= $form->field($enquiry, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Contact Number', 'class' => 'form-control'])->label(false) ?>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<?= $form->field($enquiry, 'message')->textarea(['rows' => 6, 'class' => 'form-control', 'placeholder' => 'Message'])->label(false) ?>
							</div>
						</div>
						<div class="col-12">

							<?= Html::submitButton('Send Message', ['class' => 'my-btn']) ?>

						</div>
						<?php ActiveForm::end(); ?>

					</div>
					<div class="accommodation-enquiry-image">
						<img class="img-fluid d-block w-100 wow fadeInRight" data-wow-delay="300ms" src="<?= Yii::$app->request->baseUrl; ?>/uploads/cms-data/<?= $additionalData->id; ?>/file/<?= $additionalData->file; ?>" alt="<?= $additionalData->title; ?>">
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="accommodation-gallery">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<h1 class="heading-main wow fadeInUp">Gallery</h1>
				<div class="accommodation-gallery-wrapper">
					<?php if ($accommodationData != NULL) { ?>
						<?php foreach ($accommodationData as $accommodationDat) { ?>
							<div class="gallery-item wow fadeInUp" data-wow-delay="300ms">
								<img src="<?= Yii::$app->request->baseUrl; ?>/uploads/accomodation/<?= $accommodationDat->id; ?>/image/<?= $accommodationDat->image; ?>">
								<div class="overlay-btn d-flex justify-content-center align-items-centers">
									<a href="accomodation-gallery/<?= $accommodationDat->can_name;?>">
										<div class="goto-more"><span>View All <i class="fas fa-arrow-right" aria-hidden="true"></i></span></div>
									</a>
								</div>
								<div class="name"><?= $accommodationDat->title;?></div>
							</div>
						<?php } ?>
					<?php } ?>
					
				</div>
			</div>
		</div>
	</div>
</section>