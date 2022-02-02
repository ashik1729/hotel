<?php

use common\models\CarDocuments;
use common\models\CarExtras;
use common\models\CarGeneralInformation;
use common\models\CarOptions;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<section class="rent-car-detail-page">
	<div class="container">
		<div class="row">
			<div class="col-12">
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
					<div class="rent-car-images-detail">
						<img class="img-fluid d-block w-100" src="<?php echo Yii::$app->request->baseUrl; ?>/uploads/cars/<?= $car->id; ?>/image/<?= $car->image; ?>" alt="<?= $car->title; ?>">
					</div>
					<div class="rent-car-box-detail">
						<h6><a href="<?php echo Yii::$app->request->baseUrl; ?>/rent-car">Rent a car</a> / <?= $car->title; ?></h6>
						<h1>
							<?= $car->title; ?>
						</h1>
						<h2>
							<?= $car->brand0->title; ?>, <?= $car->model_year; ?>
						</h2>
						<div class="d-flex flex-sm-row flex-column">
							<div class="car-for d-flex flex-sm-column justify-content-between align-items-center flex-row">
								Per Day
								<div class="was-price">AED <?= $car->day_price; ?></div>
								<div class="now-price">AED <?= $car->day_offer; ?></div>
							</div>
							<div class="car-for d-flex flex-sm-column justify-content-between align-items-center flex-row">
								Per Week
								<div class="was-price">AED <?= $car->week_price; ?></div>
								<div class="now-price">AED <?= $car->week_offer; ?></div>
							</div>
							<div class="car-for d-flex flex-sm-column justify-content-between align-items-center flex-row">
								Per Month
								<div class="was-price">AED <?= $car->month_price; ?></div>
								<div class="now-price">AED <?= $car->month_offer; ?></div>
							</div>
						</div>
						<p>
							<?= $car->long_description; ?>
						</p>

						<a data-bs-toggle="modal" data-bs-target="#DestailEnquiry" class="my-btn car_enquiry" href="javascript:void(0)" car_id="<?= $car->id; ?>" title="<?= $car->title; ?> <?= $car->brand0->title; ?>, <?= $car->model_year; ?>">Send Enquiry</a>

					</div>
				</div>
			</div>
			<div class="col-12">
				<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="pills-information-tab" data-bs-toggle="pill" data-bs-target="#pills-information" type="button" role="tab" aria-controls="pills-information" aria-selected="true">General Information</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="pills-options-tab" data-bs-toggle="pill" data-bs-target="#pills-options" type="button" role="tab" aria-controls="pills-options" aria-selected="false">Car options</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="pills-extras-tab" data-bs-toggle="pill" data-bs-target="#pills-extras" type="button" role="tab" aria-controls="pills-extras" aria-selected="false">Extras</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="pills-requirements-tab" data-bs-toggle="pill" data-bs-target="#pills-requirements" type="button" role="tab" aria-controls="pills-requirements" aria-selected="false">Documents Requirements</button>
					</li>
				</ul>
				<div class="tab-content" id="pills-tabContent">
					<div class="tab-pane fade show active" id="pills-information" role="tabpanel" aria-labelledby="pills-information-tab">
						<?php $carGeneralInfos = CarGeneralInformation::find()->where(['status' => 1, 'car_id' => $car->id])->all(); ?>
						<?php if ($carGeneralInfos != NULL) { ?>
							<ul class="information">
								<?php foreach ($carGeneralInfos as $carGeneralInfo) { ?>
									<li>
										<div class="d-flex justify-content-between align-items-center">
											<div class="th-item d-flex align-items-center">
												<img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/uploads/general-information/<?= $carGeneralInfo->ref->id; ?>/image/<?= $carGeneralInfo->ref->image; ?>">
												<?= $carGeneralInfo->ref->title; ?>
											</div>
											<div class="td-item">
												<?= $carGeneralInfo->value; ?>
											</div>
										</div>
									</li>
								<?php } ?>

							</ul>
						<?php } else { ?>
							<p>No Information Found</p>
						<?php } ?>

					</div>
					<div class="tab-pane fade" id="pills-options" role="tabpanel" aria-labelledby="pills-options-tab">
						<?php $carOptions = CarOptions::find()->where(['status' => 1, 'car_id' => $car->id])->all(); ?>

						<?php if ($carOptions != NULL) { ?>
							<ul class="information">
								<?php foreach ($carOptions as $carOption) { ?>
									<li>
										<div class="d-flex justify-content-between align-items-center">
											<div class="th-item d-flex align-items-center">
												<?= $carOption->ref->title; ?>
											</div>
											<div class="td-item">
												<?= $carOption->value; ?>
											</div>
										</div>
									</li>
								<?php } ?>

							</ul>
						<?php } else { ?>
							<p>No Information Found</p>
						<?php } ?>

					</div>
					<div class="tab-pane fade" id="pills-extras" role="tabpanel" aria-labelledby="pills-extras-tab">
						<?php $carExtras = CarExtras::find()->where(['status' => 1, 'car_id' => $car->id])->all(); ?>

						<?php if ($carExtras != NULL) { ?>
							<ul class="information">
								<?php foreach ($carExtras as $carExtra) { ?>
									<li>
										<div class="d-flex justify-content-between align-items-center">
											<div class="th-item d-flex align-items-center">
												<?= $carExtra->ref->title; ?>
											</div>
											<div class="td-item">
												<?= $carExtra->value; ?>
											</div>
										</div>
									</li>
								<?php } ?>

							</ul>
						<?php } else { ?>
							<p>No Information Found</p>
						<?php } ?>

					</div>
					<div class="tab-pane fade" id="pills-requirements" role="tabpanel" aria-labelledby="pills-requirements-tab">
						<?php $carDocuments = CarDocuments::find()->where(['status' => 1, 'car_id' => $car->id])->all(); ?>
						<?php if ($carDocuments != NULL) { ?>
							<ul class="document-req">
								<?php foreach ($carDocuments as $carDocument) { ?>
									<li>
										<?= $carDocument->ref->title; ?>
									</li>
								<?php } ?>

							</ul>
						<?php } else { ?>
							<p>No Information Found</p>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="modal fade car-enquire-form " id="DestailEnquiry" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">ENQUIRE NOW</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','enableClientScript' => false]]); ?>
				<?= $form->field($rentEnquiry, 'car_id')->hiddenInput(['class' => 'rent-car-id'])->label(false) ?>

				<div class="form-group">
					<?= $form->field($rentEnquiry, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Name', 'class' => 'form-control'])->label(false) ?>

				</div>
				<div class="form-group">
					<?= $form->field($rentEnquiry, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email', 'class' => 'form-control'])->label(false) ?>
				
				</div>
				<div class="form-group">
					<?= $form->field($rentEnquiry, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Phone', 'class' => 'form-control'])->label(false) ?>

				</div>
				<div class="d-flex mb-3">
					<div class="form-group radio-input">
						<?= $form->field($rentEnquiry, 'driver_status')->radio(['label' => 'With Driver', 'value' => 1, 'uncheck' => null]) ?>
					</div>
					<div class="form-group radio-input">
						<?= $form->field($rentEnquiry, 'driver_status')->radio(['label' => 'With Out Driver', 'value' => 2, 'uncheck' => null]) ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-12">
						<div class="form-group">
							<label class="label-name">Date From</label>
							<?= $form->field($rentEnquiry, 'date_from')->textInput(['maxlength' => true, 'type' => 'date', 'min' => date('Y-m-d'), 'max' => date('Y-m-d', strtotime('+5 years')), 'placeholder' => 'Phone', 'class' => 'form-control  date-area'])->label(false) ?>
						</div>
					</div>
					<div class="col-md-6 col-12">
						<div class="form-group">
							<label class="label-name">Date To</label>
							<?= $form->field($rentEnquiry, 'date_to')->textInput(['maxlength' => true, 'type' => 'date', 'min' => date('Y-m-d'), 'max' => date('Y-m-d', strtotime('+5 years')), 'placeholder' => 'Phone', 'class' => 'form-control  date-area'])->label(false) ?>

						</div>
					</div>
				</div>
				<div class="form-group">
					<?= $form->field($rentEnquiry, 'message')->textarea(['rows' => 6, 'class' => 'form-control-message rent-car-message', 'placeholder' => '']) ?>
					<!-- 
					<textarea class="form-control-message rent-car-message" placeholder="I am interested in Mercedes S600 (May bach). Please call me back"></textarea> -->
				</div>
				<?= Html::submitButton('Submit', ['class' => 'my-btn']) ?>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>