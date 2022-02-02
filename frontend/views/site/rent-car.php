<?php

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
<section class="rent-car-section">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="d-flex flex-lg-row justify-content-between flex-column">
					<div class="car-fli-cat-area d-none d-lg-block">

						<form class="">
							<div class="filter-area">
								<h4>
									Filter
								</h4>
								<?php if ($typeOfCars != NULL) { ?>
									<h6>
										Type of car:
									</h6>
									<div class="filter-items-area">
										<?php if ($typeOfCars != NULL) { ?>
											<?php foreach ($typeOfCars as $typeOfCar) { ?>
												<input <?= $_GET['type_of_car'] != NULL ? in_array($typeOfCar->id, $_GET['type_of_car']) ? "checked" : "" : "" ?> type="checkbox" class="type_of_car_checkbox" id="type_<?= $typeOfCar->id; ?>" value="<?= $typeOfCar->id; ?>" name="type_of_car[]">
												<label class="type_of_car <?= $_GET['type_of_car'] != NULL ? in_array("$typeOfCar->id", $_GET['type_of_car']) ? "checkedd" : "" : "" ?>" for="type_<?= $typeOfCar->id; ?>"><?= $typeOfCar->title; ?></label>
												<!-- <a class="filter-items" href="<?php echo Yii::$app->request->baseUrl; ?>/rent-car?type=<?= $typeOfCar->id; ?>"><?= $typeOfCar->title; ?></a> -->
											<?php } ?>
										<?php } ?>
									</div>
								<?php } ?>
								<!-- <h6>
								Filter by Pricing:
							</h6> -->
							</div>
							<?php if ($brands != NULL) { ?>
								<div class="filter-area brands-area">
									<h4>
										Brands
									</h4>
									<div class="form-group">
										<input type="checkbox" id="All Brands" value="0" name="All Brands">
										<label for="All Brands">All Brands</label>
									</div>
									<?php foreach ($brands as $brand) { ?>
										<div class="form-group">
											<input <?= $_GET['brand'] != NULL ? in_array($brand->id, $_GET['brand']) ? "checked" : "" : "" ?> type="checkbox" id="brand-<?= $brand->id; ?>" value="<?= $brand->id; ?>" name="brand[]">
											<label for="brand-<?= $brand->id; ?>"><?= $brand->title; ?></label>
										</div>

									<?php } ?>

								</div>
							<?php } ?>
							<button class="filter-apply">Apply Filter</button>
							<a href="<?php echo Yii::$app->request->baseUrl; ?>/rent-car" class="filter-clear">Clear Filter</a>
						</form>

					</div>
					<div class="md-car-filter  d-block d-lg-none">
						<a data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
							<img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/images/filter.png"> More Filter
						</a>
						<div class="offcanvas offcanvas-start package-filter" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
							<div class="offcanvas-header">
								<h5 class="offcanvas-title" id="offcanvasExampleLabel"></h5>
								<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
							</div>
							<div class="offcanvas-body">
								<form class="" action="" method="GET">
									<?php if ($typeOfCars != NULL) { ?>
										<div class="filter-area">
											<h4>
												Filter
											</h4>
											<h6>
												Type of car:
											</h6>
											<div class="filter-items-area">
												<?php if ($typeOfCars != NULL) { ?>
													<?php foreach ($typeOfCars as $typeOfCar) { ?>
														<input <?= $_GET['type_of_car'] != NULL ? in_array($typeOfCar->id, $_GET['type_of_car']) ? "checked" : "" : "" ?> type="checkbox" class="type_of_car_checkbox" id="type_<?= $typeOfCar->id; ?>" value="<?= $typeOfCar->id; ?>" name="type_of_car[]">
														<label class="type_of_car <?= $_GET['type_of_car'] != NULL ? in_array("$typeOfCar->id", $_GET['type_of_car']) ? "checkedd" : "" : "" ?>" for="type_<?= $typeOfCar->id; ?>"><?= $typeOfCar->title; ?></label>

														<!-- <a class="filter-items" href="<?php echo Yii::$app->request->baseUrl; ?>/rent-car?type=<?= $typeOfCar->id; ?>"><?= $typeOfCar->title; ?></a> -->
													<?php } ?>
												<?php } ?>
											</div>
											<h6>
												Filter by Pricing:
											</h6>
										</div>
									<?php } ?>
									<?php if ($brands != NULL) { ?>
										<div class="filter-area brands-area">
											<h4>
												Brands
											</h4>

											<div class="form-group">
												<input type="checkbox" id="All Brands" value="0" name="All Brands">
												<label for="All Brands">All Brands</label>
											</div>
											<?php foreach ($brands as $brand) { ?>
												<div class="form-group">
													<input <?= $_GET['brand'] != NULL ? in_array($brand->id, $_GET['brand']) ? "checked" : "ashik" : "ali" ?> type="checkbox" id="brand-<?= $brand->id; ?>" value="<?= $brand->id; ?>" name="brand[]">

													<label for="brand-<?= $brand->id; ?>"><?= $brand->title; ?></label>
												</div>

											<?php } ?>

										</div>
									<?php } ?>
									<button type="submit" class="filter-apply">Apply Filter</button>
									<a href="<?php echo Yii::$app->request->baseUrl; ?>/rent-car" class="filter-clear">Clear Filter</a>
								</form>
							</div>
						</div>
					</div>
					<div class="car-item-list ">
						<?php if (Yii::$app->session->hasFlash("success")) : ?>				
							<div class="alert alert-success alert-dismissible">
								<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
								<span>	<?= Yii::$app->session->getFlash("success") ?></span>
							</div>
						<?php endif; ?>
						<?php if (Yii::$app->session->hasFlash("error")) : ?>				
							<div class="alert alert-danger alert-dismissible">
								<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
								<span>	<?= Yii::$app->session->getFlash("error") ?></span>
							</div>
						<?php endif; ?>
						<div class="car-list-head">
							<div class="d-flex flex-sm-row justify-content-sm-between justify-content-sm-center align-items-sm-center flex-column">
								<div class="car-head">
									<h1>Rent a car</h1>
									<h6><?= count($cars); ?> Vehicles found</h6>
								</div>
								<div class="car-sort">
									<h6>Sort By</h6>
									<form class="sort_form" method="GET" action="">
										<select name="sorting" class="form-control select-form cart_sort_filter">
											<option value="1" <?= $_GET['sorting'] == 1 ? "selected" : "" ?>>Model Year - New to Old</option>
											<option value="2" <?= $_GET['sorting'] == 2 ? "selected" : "" ?>>Model Year - Old to New</option>
										</select>
									</form>
								</div>
							</div>
						</div>
						<div class="car-list-wrapper">
							<?php if ($cars != NULL) { ?>
								<?php foreach ($cars as $car) { ?>
									<div class="car-item-box">
										<div class="img-box">
											<img class="img-fluid d-block w-100" src="<?php echo Yii::$app->request->baseUrl; ?>/uploads/cars/<?= $car->id; ?>/image/<?= $car->image; ?>" alt="<?= $car->title; ?>">
										</div>
										<h3><?= $car->title; ?></h3>
										<h6><?= $car->brand0->title; ?>, <?= $car->model_year; ?></h6>
										<div class="d-flex flex-md-row justify-content-md-between align-items-md-center flex-sm-column">
											<div class="car-for d-flex flex-md-column flex-sm-row flex-column">
												Per Day
												<div class="was-price">AED <?= $car->day_price; ?></div>
												<div class="now-price">AED <?= $car->day_offer; ?></div>
											</div>
											<div class="car-for d-flex flex-md-column flex-sm-row flex-column">
												Per Week
												<div class="was-price">AED <?= $car->week_price; ?></div>
												<div class="now-price">AED <?= $car->week_offer; ?></div>
											</div>
											<div class="car-for d-flex flex-md-column flex-sm-row flex-column">
												Per Month
												<div class="was-price">AED <?= $car->month_price; ?></div>
												<div class="now-price">AED <?= $car->month_offer; ?></div>
											</div>
										</div>
										<div class="car-overlay d-flex flex-column align-items-center justify-content-center">
											<a class="car-btn" href="rent-car-details/<?= $car->can_name; ?>">Learn More</a>
											<a data-bs-toggle="modal" data-bs-target="#DestailEnquiry" class="car-btn2 car_enquiry" href="javascript:void(0)" car_id="<?= $car->id; ?>" title="<?= $car->title; ?> <?= $car->brand0->title; ?>, <?= $car->model_year; ?>">Send Enquiry</a>
											<!-- <a class="car-btn2" href="" data-bs-toggle="modal" data-bs-target="#DestailEnquiry">Send Enquiry</a> -->
										</div>
									</div>
								<?php } ?>
							<?php } ?>
							<!--  -->

						</div>
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
				<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
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