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
$middleDataOne = CmsData::findOne(['page_id' => $model->id, 'can_name' => 'event-one']);
$middleDatatwo = CmsData::findOne(['page_id' => $model->id, 'can_name' => 'event-two']);
?><section class="accommodation-provide">
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

<section class="accommodation-enquiry">
	<div class="container">
		<div class="row">
		
			<div class="col-12">
				<div class="d-flex flex-lg-row justify-content-between flex-column">
					<div class="accommodation-enquiry-form">
						<h1 class="heading-main wow fadeInUp">
							Get In Touch<br>With Us!
						</h1>

						<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'enableClientScript' => false, 'class' => "wow fadeInUp organize-event", 'data-wow-delay' => "300ms"]]); ?>

						<div class="col-12">
							<div class="form-group">
								<?= $form->field($eventRequest, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Full Name', 'class' => 'form-control'])->label(false) ?>

							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<?= $form->field($eventRequest, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Contact Number', 'class' => 'form-control'])->label(false) ?>

							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<?= $form->field($eventRequest, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email Address', 'class' => 'form-control'])->label(false) ?>

							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>
									Event Date*
								</label>
								<?= $form->field($eventRequest, 'date')->textInput(['maxlength' => true, 'type' => 'date', 'min' => date('Y-m-d'), 'max' => date('Y-m-d', strtotime('+5 years')), 'placeholder' => 'Check In Date', 'class' => 'form-control  date-area date-picker'])->label(false) ?>

							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<?= $form->field($eventRequest, 'no_adult')->textInput(['maxlength' => true, 'placeholder' => 'No Of People', 'class' => 'form-control'])->label(false) ?>

							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<?php
								echo $form->field($eventRequest, 'event_id')->dropDownList(ArrayHelper::map(\common\models\Events::find()->all(), 'id', 'title'), ['class' => 'form-control  select-form', 'prompt' => 'Choose Event Type'])->label(false);
								?>

							</div>
						</div>
						<div class="col-12">
							<?= Html::submitButton('Send Message', ['class' => 'my-btn']) ?>
						</div>
						<?php ActiveForm::end(); ?>
					</div>
					<div class="accommodation-enquiry-image">
						<img class="img-fluid d-block w-100 wow fadeInRight" src="<?php echo Yii::$app->request->baseUrl; ?>/uploads/cms-data/<?= $middleDatatwo->id; ?>/file/<?= $middleDatatwo->file; ?>" alt="<?= $model->title; ?>">
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
					<?php if ($eventData != NULL) { ?>
						<?php foreach ($eventData as $eventDat) { ?>
							<div class="gallery-item wow fadeInUp" data-wow-delay="300ms">
								<img src="<?= Yii::$app->request->baseUrl; ?>/uploads/events/<?= $eventDat->id; ?>/image/<?= $eventDat->image; ?>">
								<div class="overlay-btn d-flex justify-content-center align-items-centers">
									<a href="event-gallery/<?= $eventDat->can_name; ?>">
										<div class="goto-more"><span>View All <i class="fas fa-arrow-right" aria-hidden="true"></i></span></div>
									</a>
								</div>
								<div class="name"><?= $eventDat->title; ?></div>
							</div>
						<?php } ?>
					<?php } ?>

				</div>
			</div>
		</div>
	</div>
</section>