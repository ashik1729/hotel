<?php

use Codeception\Step;
use common\models\CmsData;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>

<section class="banner text-center">
    <img class="banner-img" src="images/visa-detial-image.jpg" alt="HCCA Visa Banner">
    <img class="banner-img" src="<?php echo Yii::$app->request->baseUrl; ?>/uploads/visa/<?= $visa->id ?>/image/<?= $visa->image; ?>" alt="<?= $visa->title; ?>">

    <h3>Book</h3>
    <h1 class="heading-main"><?= $visa->title; ?></h1>
</section>

<section class="visa_detail_section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex flex-lg-row justify-content-lg-between flex-column">
                    <div class="visa_details-wrapper">
                        <div class="detail-slider-area" style="padding-bottom: 40px;">
                            <section id="demos">
                                <div class="inner__slider owl-carousel owl-theme">

                                    <?php if ($visa->gallery != '') {
                                        $images = explode(',', $visa->gallery);
                                        $result_html = '';
                                        if ($images != NULL) {
                                            foreach ($images as $image) {
                                                $img = Yii::$app->request->baseUrl . '/uploads/visa/' . $visa->id . '/gallery/' . $image;
                                    ?>
                                                <div class="item text-center">
                                                    <img class="img-fluid d-block w-100" src="<?= $img; ?>" alt="<?= $visa->title; ?>">
                                                </div>

                                    <?php }
                                        }
                                    } ?>

                                </div>
                            </section>
                            <!-- <img class="img-fluid d-block w-100" src="images/package-detail/package-detail-01.jpg"> -->
                        </div>
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
                        <div class="package-more-info">
                            <?= $visa->short_description; ?>

                        </div>
                        <div class="package-more-info">
                            <?= $visa->long_description; ?>

                        </div>
                        <div class="package-more-info">
                            <h4 class="mt-4">FAQ</h4>
                            <div class="accordion" id="accordionExample">
                                <?php if ($visafaq != NULL) { ?>
                                    <?php foreach ($visafaq as $faq) { ?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne-<?= $faq->id; ?>">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne-<?= $faq->id; ?>" aria-expanded="true" aria-controls="collapseOne-<?= $faq->id; ?>">
                                                    <?= $faq->question; ?>
                                                </button>
                                            </h2>
                                            <div id="collapseOne-<?= $faq->id; ?>" class="accordion-collapse collapse show" aria-labelledby="headingOne-<?= $faq->id; ?>" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <?= $faq->answer; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>

                            </div>
                        </div>
                    </div>

                    <div class="quick__visa-area">
                        <div class="sticky-lg-top sticky-lg-top-90">
                            <div class="visa_choose_area">
                                <h1>Choose Visa Options</h1>

                                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'enableClientScript' => false]]); ?>
                                <div class="form-group d-flex flex-sm-row justify-content-sm-center align-items-center flex-column">
                                    <label>
                                        Visa Option
                                    </label>
                                    <?php
                                    echo $form->field($visaRequest, 'visa_option')->dropDownList(ArrayHelper::map(\common\models\VisaOption::find()->all(), 'id', 'title'), ['class' => 'form-control', 'prompt' => 'Choose Visa Option'])->label(false);
                                    ?>

                                </div>
                                <div class="form-group d-flex flex-sm-row justify-content-sm-center align-items-center flex-column">
                                    <label>
                                        Processing Type
                                    </label>
                                    <?php
                                    echo $form->field($visaRequest, 'processing_type')->dropDownList(ArrayHelper::map(\common\models\ProcessingType::find()->all(), 'id', 'title'), ['class' => 'form-control', 'prompt' => 'Choose Processing Type'])->label(false);
                                    ?>

                                </div>
                                <div class="form-group d-flex flex-sm-row justify-content-sm-center align-items-center flex-column">
                                    <label>
                                        No. Of Visa
                                    </label>
                                    <div class="counter-area space-right">
                                        <div class="quantity-counter">
                                            <a class="btn btn-quantity-down">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus">
                                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                                </svg>
                                            </a>
                                            <?= $form->field($visaRequest, 'no_visa')->textInput(['maxlength' => true, 'min' => 1, 'max' => 100, 'Step' => 1, 'value' => 1, 'type' => 'number', 'placeholder' => 'Phone', 'class' => 'input-number__input form-control2 form-control-lg'])->label(false) ?>

                                            <a class="btn btn-quantity-up">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
                                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group d-flex flex-sm-row justify-content-sm-center align-items-center flex-column">
                                    <label>
                                        Travel Date From
                                    </label>
                                    <div class="date space-right">
                                        <?= $form->field($visaRequest, 'travel_date_from')->textInput(['maxlength' => true, 'type' => 'date', 'min' => date('Y-m-d'), 'max' => date('Y-m-d', strtotime('+5 years')), 'placeholder' => 'Date From', 'class' => 'form-control date-area date-picker2'])->label(false) ?>

                                    </div>
                                </div>
                                <div class="form-group d-flex flex-sm-row justify-content-sm-center align-items-center flex-column">
                                    <label>
                                        Travel Date To
                                    </label>
                                    <div class="date space-right">
                                        <?= $form->field($visaRequest, 'travel_date_to')->textInput(['maxlength' => true, 'type' => 'date', 'min' => date('Y-m-d'), 'max' => date('Y-m-d', strtotime('+5 years')), 'placeholder' => 'Date To', 'class' => 'form-control date-area date-picker2'])->label(false) ?>

                                    </div>
                                </div>

                                <div class="amount-area d-flex justify-content-between align-items-center">
                                    <h6>Price</h6>
                                    <div class="amount">AED <?= $visa->price; ?></div>
                                </div>
                                <?php if (!Yii::$app->user->isGuest) { ?>
                                    <?= Html::submitButton('Submit', ['class' => 'enquiry-btn']) ?>
                                <?php } else { ?>
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#LoginEnquiry" class="enquiry-btn">Submit</button>
                                <?php } ?>
                                <?php ActiveForm::end(); ?>



                            </div>
                            <div class="call-area">
                                <h2>UAE CALL CENTER</h2>
                                <ul>
                                    <li>
                                        <a href="tel: <?= $visa->whatsapp_no; ?>">
                                            <img src="<?php echo Yii::$app->request->baseUrl; ?>/images/call-01.png" alt="<?= $visa->title; ?>">
                                            <?= $visa->whatsapp_no; ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="mail:<?= $visa->email; ?>">
                                            <img src="<?php echo Yii::$app->request->baseUrl; ?>/images/call-02.png" alt="<?= $visa->title; ?>">

                                            <?= $visa->email; ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="tel:<?= $visa->phone_no; ?>">
                                            <img src="<?php echo Yii::$app->request->baseUrl; ?>/images/call-03.png" alt="<?= $visa->title; ?>">
                                            <?= $visa->phone_no; ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
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