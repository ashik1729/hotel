<?php

use common\models\PackagesDate;
use common\models\PackagesPrice;
use yii\bootstrap4\ActiveForm;

$packageDates = PackagesDate::find()->where(['package_date' => date('Y-m-d'), 'package_id' => $model->id])->all();

?>
<?php if ($packageDates != NULL) { ?>

<?php } ?>
<script>
	// var dateData = [];
	// dateData.push([{
	// 	title: 'AED 4500',
	// 	start: '2022-02-04'
	// }, {
	// 	title: 'AED 490',
	// 	start: '2022-02-5'
	// }])

	var dateData = [];

	// append new value to the array
	<?php foreach ($packageDates as $packageDate) {
		$packageDatesPrices = PackagesPrice::find()->where(['package_date_id' => $packageDate->id])->all();
		if ($packageDatesPrices != NULL) {
			foreach ($packageDatesPrices as $packageDatesPrice) {
	?>
				dateData.push({
					title: 'AED <?= $packageDatesPrice->price; ?>',
					start: '<?php echo $packageDate->package_date; ?>'
				});
			<?php } ?>
		<?php } ?>
	<?php } ?>
</script>
<section class="banner text-center">
	<img class="banner-img" src="<?php

									echo Yii::$app->request->baseUrl; ?>/uploads/product-banner/<?= $model->id; ?>/image/<?= $model->banner_image; ?>" alt="HCCA Packages Banner">
	<h3><?php echo $model->package_title; ?></h3>
	<h1 class="heading-main"><?php echo $model->destinations->title; ?></h1>
</section>
<section class="book-package-section">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="d-flex flex-lg-row justify-content-between flex-column">
					<div class="book-package-section-calendar">
						<h1>Burj Khalifa and Dubai Fountains</h1>

						<script>
							document.addEventListener('DOMContentLoaded', function() {
								var calendarEl = document.getElementById('calendar');

								var calendar = new FullCalendar.Calendar(calendarEl, {
									initialDate: '2022-01-19',
									editable: true,
									selectable: true,
									businessHours: true,
									dayMaxEvents: true, // allow "more" link when too many events
									events: dateData,
									dateClick: function(info) {
										//document.getElementsByClassName("package_date").value = info.dateStr;
										document.getElementById('package_date').value = info.dateStr;
										// alert('Coordinates: ' + info.jsEvent.pageX + ',' + info.jsEvent.pageY);
										// alert('Current view: ' + info.view.type);
										// change the day's background color just for fun
										// info.dayEl.style.backgroundColor = 'red';
									}
									// events: [{
									// 		title: 'AED 4500',
									// 		start: '2022-02-04'
									// 	},
									// 	// {
									// 	//   title: '$4500',
									// 	//   start: '2022-01-19T10:30:00',
									// 	//   end: '2022-01-31T12:30:00'
									// 	// },
									// 	// {
									// 	//   title: 'Long Event',
									// 	//   start: '2020-09-07',
									// 	//   end: '2020-09-10'
									// 	// },
									// 	// {
									// 	//   groupId: 999,
									// 	//   title: 'Repeating Event',
									// 	//   start: '2020-09-09T16:00:00'
									// 	// },
									// 	// {
									// 	//   groupId: 999,
									// 	//   title: 'Repeating Event',
									// 	//   start: '2020-09-16T16:00:00'
									// 	// },
									// 	// {
									// 	//   title: 'Conference',
									// 	//   start: '2020-09-11',
									// 	//   end: '2020-09-13'
									// 	// },
									// 	// {
									// 	//   title: 'Meeting',
									// 	//   start: '2020-09-12T10:30:00',
									// 	//   end: '2020-09-12T12:30:00'
									// 	// },
									// 	// {
									// 	//   title: 'Lunch',
									// 	//   start: '2020-09-12T12:00:00'
									// 	// },
									// 	// {
									// 	//   title: 'Meeting',
									// 	//   start: '2020-09-12T14:30:00'
									// 	// },
									// 	// {
									// 	//   title: 'Happy Hour',
									// 	//   start: '2020-09-12T17:30:00'
									// 	// },
									// 	// {
									// 	//   title: 'Dinner',
									// 	//   start: '2020-09-12T20:00:00'
									// 	// },
									// 	// {
									// 	//   title: 'Birthday Party',
									// 	//   start: '2020-09-13T07:00:00'
									// 	// },
									// 	// {
									// 	//   title: 'Click for Google',
									// 	//   url: 'http://google.com/',
									// 	//   start: '2020-09-28'
									// 	// }
									// ]
								});

								calendar.render();
							});
						</script>

						<div id='calendar'>

						</div>

						<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'enableClientScript' => false]]); ?>
						<?= $form->field($cart, 'date')->textInput(['type' => 'hidden', 'id' => 'package_date'])->label(false) ?>

						<button type="submit" class="calendar-btn">Next <i class="fas fa-arrow-right"></i></button>
						<?php ActiveForm::end(); ?>
						<!-- <button class="calendar-btn">Next <i class="fas fa-arrow-right"></i></button> -->
					</div>
					<div class="book-package-section-details">

						<?php if ($model->gallery != '') {
							$images = explode(',', $model->gallery);
							$result_html = '';
							if ($images != NULL) {
								foreach ($images as $image) {

									$img = Yii::$app->request->baseUrl . '/uploads/products/' . base64_encode($model->id) . '/gallery/' . $image;
						?>
									<div class="img-box">
										<img class="img-fluid" src="<?= $img; ?>" alt="<?= $visa->title; ?>">
									</div>


						<?php }
							}
						} ?>

						<?php echo $model->short_description_en; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>