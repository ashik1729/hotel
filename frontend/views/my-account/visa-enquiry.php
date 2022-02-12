<?php

use common\models\ProductReview;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

echo $this->render('account-menu', ['active' => 'visa-enquiry']); ?>
<section class="my-account-detials">
    <div class="container">
        <div class="row">
            <div class="col-12 d-flex flex-lg-row justify-content-between flex-column">
                <h1>Visa Enquiries</h1>
                <div class="my-account-sort d-flex flex-sm-row justify-content-between align-items-sm-center flex-column">
                    Visa enquiries history
                    <?php $form = ActiveForm::begin(['id' => 'time_sort', 'method' => 'GET', 'action' => Yii::$app->request->baseUrl . '/visa-enquiry', 'options' => ['enctype' => 'multipart/form-data', 'enableClientScript' => false, 'class' => 'time_sort']]); ?>
                    <select required="" name="period" class="form-control select-form space-right period">
                        <option value="">Choose</option>
                        <option <?= (isset($_REQUEST['period']) && $_REQUEST['period'] == 1 ? "selected" : ""); ?> value="1">Past 6 months</option>
                        <option <?= (isset($_REQUEST['period']) && $_REQUEST['period'] == 2 ? "selected" : ""); ?> value="2">Past 1 year</option>
                    </select>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <div class="col-12 tour-package-history-table">
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
                <?php if ($visaEnquiry != NULL) { ?>
                    <?php foreach ($visaEnquiry as $visaEnq) {
								$checkFeedback = ProductReview::find()->where(['user_id'=>Yii::$app->user->id,'review_type'=>2,'review_for_id'=>$visaEnq->id])->one();

                    ?>
                        <table class="cart-table__table ">
                            <tbody class="cart-table__body">
                                <tr class="cart-table__row">
                                    <td class="cart-table__column cart-table__column--image">
                                        <div class="img-box">
                                            <img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/uploads/visa/<?php echo  $visaEnq->visa_id; ?>/image/<?= $visaEnq->visa->image; ?>">
                                        </div>
                                    </td>
                                    <td class="cart-table__column cart-table__column--details">
                                        <p class="title"><?= $visaEnq->visa->title; ?></p>
                                        <p><?= $visaEnq->travel_date_from; ?> - <?= $visaEnq->travel_date_to; ?> </p>
                                    </td>
                                    <td class="cart-table__column cart-table__column--people">
                                        <p>Total AED <?= $visaEnq->visa->price; ?></p>
                                    </td>
                                    <td class="cart-table__column cart-table__column--action">
                                        <div>ORDER # <?= $visaEnq->id; ?></div>
                                        <?php if ($checkFeedback == NULL) { ?>
                                            <a class="leave leave_feedback" href="" data-bs-toggle="modal" visa_id="<?= $visaEnq->id; ?>" data-bs-target="#feedBack">Leave feedback</a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</section>


<div class="modal fade login-enquire-form" id="feedBack" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Submit Your Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'feedback-form',
                    // 'action' => Yii::$app->request->baseUrl . '/my-account/leave-feedback',
                    // 'enableAjaxValidation' => true,
                    'enableClientValidation' => true,
                    // 'validationUrl' => 'validation-rul',
                    'options' => ['enctype' => 'multipart/form-data', 'enableClientScript' => false]
                ]); ?>
                <?php if (isset($productReview->errors) && $productReview->errors != NULL) { ?>
                    <?php echo  $form->errorSummary($productReview); ?>
                <?php } ?>
                <!-- <label for="productreview-author">Rating</label> -->
                <div class="rate">
                    <input type="radio" id="star5" name="ProductReview[rating]" value="5" />
                    <label for="star5" title="text">5 stars</label>
                    <input type="radio" id="star4" name="ProductReview[rating]" value="4" />
                    <label for="star4" title="text">4 stars</label>
                    <input type="radio" id="star3" name="ProductReview[rating]" value="3" />
                    <label for="star3" title="text">3 stars</label>
                    <input type="radio" id="star2" name="ProductReview[rating]" value="2" />
                    <label for="star2" title="text">2 stars</label>
                    <input type="radio" id="star1" name="ProductReview[rating]" value="1" />
                    <label for="star1" title="text">1 star</label>
                </div>

                <div class="form-group">
                    <spa id="ashikali"></spa>
                    <?= $form->field($productReview, 'review_for_id')->hiddenInput(['maxlength' => true, 'placeholder' => 'Full Name',  'class' => 'form-control review_for_id'])->label(false) ?>
                    <?= $form->field($productReview, 'author')->textInput(['maxlength' => true, 'placeholder' => 'Full Name', 'value' => Yii::$app->user->identity->first_name, 'class' => 'form-control'])->label(false) ?>
                </div>
                <div class="form-group">
					
					<?= $form->field($productReview, 'designation')->textInput(['maxlength' => true, 'placeholder' => 'Designation', 'class' => 'form-control'])->label(false) ?>
				</div>
                <?= $form->field($productReview, 'comment')->textarea(['rows' => 6, 'class' => 'form-control-message', 'placeholder' => 'Message'])->label(false) ?>

                <?= Html::submitButton('Submit Your Review ', ['class' => 'my-btn ']) ?>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>

<?php

if (isset($productReview->errors) && $productReview->errors != NULL) {
    $this->registerJs(
        <<< EOT_JS_CODE
        $('#feedBack').modal('show');
EOT_JS_CODE
    );
}

$this->registerJs(
    <<< EOT_JS_CODE
	$('.leave_feedback').click(function(){
		var package_id = $(this).attr('visa_id');
		$('.review_for_id').val(package_id);
	});
EOT_JS_CODE
);
?>