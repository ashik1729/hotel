<?php

use yii\bootstrap4\ActiveForm;

echo $this->render('account-menu',['active'=>'visa-enquiry']); ?>
<section class="my-account-detials">
    <div class="container">
        <div class="row">
            <div class="col-12 d-flex flex-lg-row justify-content-between flex-column">
                <h1>Visa Enquiries</h1>
                <div class="my-account-sort d-flex flex-sm-row justify-content-between align-items-sm-center flex-column">
                    Visa enquiries history
                    <?php $form = ActiveForm::begin(['id'=>'time_sort','method'=>'GET','action'=>Yii::$app->request->baseUrl.'/visa-enquiry','options' => ['enctype' => 'multipart/form-data', 'enableClientScript' => false,'class'=>'time_sort']]); ?>
                    <select required="" name="period" class="form-control select-form space-right period">
                        <option  value="">Choose</option>
                        <option <?= (isset($_REQUEST['period']) && $_REQUEST['period'] ==1 ? "selected":"");?> value="1">Past 6 months</option>
                        <option <?= (isset($_REQUEST['period']) && $_REQUEST['period'] ==2 ? "selected":"");?> value="2">Past 1 year</option>
                    </select>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <div class="col-12 tour-package-history-table">
                <?php if ($visaEnquiry != NULL) { ?>
                    <?php foreach ($visaEnquiry as $visaEnq) {
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
                                        <a class="leave" href="">Leave feedback</a>
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