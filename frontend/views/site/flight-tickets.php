<?php

use common\models\CmsData;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<?php if ($model != NULL) { ?>
	<section class="banner text-center">
		<img class="banner-img" src="<?php echo Yii::$app->request->baseUrl; ?>/uploads/cms/<?= $model->id ?>/image/<?= $model->image; ?>" alt="<?= $model->title; ?>">
		<h1 class="heading-main"><?= $model->subtitle; ?></h1>
	</section>
<?php } ?>

<?php
$middleDataOne = CmsData::findOne(['page_id' => $model->id, 'can_name' => 'flight-one']);
?>
<section class="accommodation-provide">
	<div class="container">
		<div class="row">
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
			<div class="col-12">
				<div class="d-flex flex-lg-row justify-content-between flex-column">
					<div class="accommodation-image wow fadeInLeft" data-wow-delay="300ms">
						<img src="<?php echo Yii::$app->request->baseUrl; ?>/uploads/cms-data/<?= $middleDataOne->id; ?>/file/<?= $middleDataOne->file; ?>" alt="<?= $model->title; ?>">
					</div>
					<div class="accommodation-detail">
						<?php echo $model->short_description; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="book-flight-section">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<h1>Get In Touch With Us!</h1>
			</div>
			<div class="col-12">
				<div class="get-in-touch-area row">
					<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'enableClientScript' => false, 'class' => 'row']]); ?>
					<div class="col-lg-6 col-12">
						<label>
							Name*
						</label>
						<?= $form->field($flgihtRequest, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Full Name', 'class' => 'form-control'])->label(false) ?>
					</div>
					<div class="col-lg-6 col-12">
						<label>
							Email*
						</label>
						<?= $form->field($flgihtRequest, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email Address', 'class' => 'form-control'])->label(false) ?>
					</div>
					<div class="col-lg-6 col-12">
						<label>
							Phone*
						</label>
						<?= $form->field($flgihtRequest, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Phone Number', 'class' => 'form-control'])->label(false) ?>
					</div>
					<div class="col-lg-6 col-12">
						<label>
							Travel Date*
						</label>
						<?= $form->field($flgihtRequest, 'checkin_date')->textInput(['maxlength' => true, 'type' => 'date', 'min' => date('Y-m-d'), 'max' => date('Y-m-d', strtotime('+5 years')), 'placeholder' => 'Check In Date', 'class' => 'form-control  date-area date-picker'])->label(false) ?>
					</div>
					<div class="col-lg-6 col-12">
						<label>
							From*
						</label>
						<?= $form->field($flgihtRequest, 'from_place')->textInput(['maxlength' => true, 'placeholder' => 'From Place', 'class' => 'form-control'])->label(false) ?>
					</div>
					<div class="col-lg-6 col-12">
						<label>
							To*
						</label>
						<?= $form->field($flgihtRequest, 'to_place')->textInput(['maxlength' => true, 'placeholder' => 'To Place', 'class' => 'form-control'])->label(false) ?>

					</div>
					<div class="col-lg-6 col-12 p-n-area">
						<label>
							People*
						</label>
						<div class="d-flex flex-md-row justify-content-md-between flex-column">
							<div class="d-flex justify-content-between justify-content-center align-items-center counter-small-mt">
								<label>Adults</label>
								<div class="counter-area">
									<div class="quantity-counter">
										<a class="btn btn-quantity-down">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus">
												<line x1="5" y1="12" x2="19" y2="12"></line>
											</svg>
										</a>
										<!-- <input type="number" class="input-number__input form-control2 form-control-lg" min="1" max="100" step="1" value="1"> -->

										<?= $form->field($flgihtRequest, 'no_adult')->textInput(['maxlength' => true, 'min' => "1", "max" => 1, "value" => 1, "step" => 1, 'class' => 'input-number__input form-control2 form-control-lg', 'type' => 'number'])->label(false) ?>
										<a class="btn btn-quantity-up">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
												<line x1="12" y1="5" x2="12" y2="19"></line>
												<line x1="5" y1="12" x2="19" y2="12"></line>
											</svg>
										</a>
									</div>
								</div>
							</div>
							<div class="d-flex justify-content-between justify-content-center align-items-center ">
								<label>Children</label>
								<div class="counter-area">
									<div class="quantity-counter">
										<a class="btn btn-quantity-down">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus">
												<line x1="5" y1="12" x2="19" y2="12"></line>
											</svg>
										</a>
										<?= $form->field($flgihtRequest, 'no_children')->textInput(['maxlength' => true, 'min' => "1", "max" => 1, "value" => 1, "step" => 1, 'class' => 'input-number__input form-control2 form-control-lg', 'type' => 'number'])->label(false) ?>


										<a class="btn btn-quantity-up">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
												<line x1="12" y1="5" x2="12" y2="19"></line>
												<line x1="5" y1="12" x2="19" y2="12"></line>
											</svg>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6 col-12">
						<label>
							Event Type*
						</label>
						<?= $form->field($flgihtRequest, 'class')->dropDownList(
							[
								'Economy' => 'Economy',
								'Premium Economy' => 'Premium Economy',
								'Business Class' => 'Business Class',
								'First Class' => 'First Class'
							],
							['prompt' => 'Class Type', 'class' => 'form-control select select-form']
						)->label(false) ?>
					</div>
					<div class="col-12 text-center">
						<?= Html::submitButton('Send', ['class' => 'my-btn']) ?>

					</div>

					<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
	</div>
</section>