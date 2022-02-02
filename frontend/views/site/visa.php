<?php

use common\models\CmsData;

if ($model != NULL) { ?>
    <section class="banner text-center">
        <img class="banner-img" src="<?php echo Yii::$app->request->baseUrl; ?>/uploads/cms/<?= $model->id ?>/image/<?= $model->image; ?>" alt="<?= $model->title; ?>">
        <h3><?= $model->title; ?></h3>
        <h1 class="heading-main"><?= $model->subtitle; ?></h1>
        <div class="row no-padding">
            <div class="col-lg-4 offset-lg-4 col-md-8 offset-md-2  offset-1 col-10">
                <div class="banner-form ">
                    <!-- <form class="w-100 d-flex justify-content-between align-items-center">
                    <input class="form-control" type="text" name="" placeholder="Search">
                    <button class="banner-btn" type="button"><img src="images/search.png"></button>
                </form> -->
                </div>
            </div>
        </div>
    </section>
<?php } ?>

<?php
$middleDataOne = CmsData::findOne(['page_id' => $model->id, 'can_name' => 'best-price-guarantee']);
$middleDatatwo = CmsData::findOne(['page_id' => $model->id, 'can_name' => '24x7-live-chat-support']);
$middleDatathree = CmsData::findOne(['page_id' => $model->id, 'can_name' => 'easy-online-transaction']);
$middleDataOneFour = CmsData::findOne(['page_id' => $model->id, 'can_name' => 'secure-online-transaction']);
$middleDataOneFive = CmsData::findOne(['page_id' => $model->id, 'can_name' => 'guest-reviews-ratings']);
?>

<section class="visa_reason">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>REASON TO BOOK WITH US </h1>
                <div class="visa_reason-wrapper">
                    <div class="visa-item">
                        <div class="sm-screen">
                            <div class="img-box">
                                <img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/uploads/cms-data/<?= $middleDataOne->id ?>/file/<?= $middleDataOne->file; ?>" alt="<?= $middleDataOne->title; ?>">
                            </div>
                            <h5>
                                <?= $middleDataOne->field_one; ?>
                            </h5>
                        </div>
                    </div>
                    <div class="visa-item">
                        <div class="sm-screen">
                            <div class="img-box">
                                <img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/uploads/cms-data/<?= $middleDatatwo->id ?>/file/<?= $middleDatatwo->file; ?>" alt="<?= $middleDatatwo->title; ?>">
                            </div>
                            <h5>
                                <?= $middleDatatwo->field_one; ?>
                            </h5>
                        </div>
                    </div>
                    <div class="visa-item">
                        <div class="sm-screen">
                            <div class="img-box">
                                <img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/uploads/cms-data/<?= $middleDatathree->id ?>/file/<?= $middleDatathree->file; ?>" alt="<?= $middleDatathree->title; ?>">
                            </div>
                            <h5>
                                <?= $middleDatathree->field_one; ?>
                            </h5>
                        </div>
                    </div>
                    <div class="visa-item">
                        <div class="sm-screen">
                            <div class="img-box">
                                <img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/uploads/cms-data/<?= $middleDataOneFour->id ?>/file/<?= $middleDataOneFour->file; ?>" alt="<?= $middleDataOneFour->title; ?>">
                            </div>
                            <h5>
                                <?= $middleDataOneFour->field_one; ?>
                            </h5>
                        </div>
                    </div>
                    <div class="visa-item">
                        <div class="sm-screen">
                            <div class="img-box">
                                <img class="img-fluid" src="<?php echo Yii::$app->request->baseUrl; ?>/uploads/cms-data/<?= $middleDataOneFive->id ?>/file/<?= $middleDataOneFive->file; ?>" alt="<?= $middleDataOneFive->title; ?>">
                            </div>
                            <h5>
                                <?= $middleDataOneFive->field_one; ?>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="visa_list">
    <div class="row no-padding">
        <div class="col-12 text-center">
            <h1 class="heading-main"><?= $model->short_description; ?></h1>
        </div>
        <div class="col-12 no-padding">
            <div class="visa_list-wrapper">
                <?php if ($visas != NULL) { ?>
                    <?php foreach ($visas as $visa) { ?>
                        <div class="visa">
                            <img class="visa-image" src="<?php echo Yii::$app->request->baseUrl; ?>/uploads/visa/<?= $visa->id ?>/image/<?= $visa->image; ?>" alt="<?= $visa->title;?>">
                            <div class="overlay_shadow">
                            </div>
                            <div class="content-area">
                                <h4 class="text-upper"><?php echo $visa->title;?></h4>
                                <a class="wow fadeInUp" href="<?php echo Yii::$app->request->baseUrl; ?>/visa-details/<?= $visa->can_name;?>">
                                    <div class="goto-more">view more <i class="fas fa-arrow-right"></i></div>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
                
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