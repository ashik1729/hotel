<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Orders */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-sm-12 margin_auto">
        <div class="order_status">
            <div class="row">
                <div class="col-sm-12">
                    <ul class="status_stage_parent list-unstyled">
                        <li class="status_stage_title text-left">Order Placed</li>
                        <li class="status_stage_title text-left">Order Packed</li>
                        <li class="status_stage_title text-center">Order Shipped</li>
                        <li class="status_stage_title text-right">Order Delivered</li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <ul class="status_stage_parent list-unstyled">
                        <li class="status_stage "></li>
                        <li class="status_stage"></li>
                        <li class="status_stage"></li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="row">


    <hr>
    <div class="col-sm-12 margin_auto">
        <div class="order_status_content">
            <div class="row">
                <div class="col-sm-4">
                    <h6><b>Date</b></h6>

                </div>
                <div class="col-sm-4">
                    <h6><b>Time</b></h6>

                </div>

                <div class="col-sm-4">

                    <h6><b>Status</b></h6>

                </div>
            </div>
            <?php foreach ($models as $model) { ?>
                <div class="row">

                    <div class="col-sm-4">

                        <p><?php echo date('Y-m-d', strtotime($model->created_at)); ?></p>

                    </div>
                    <div class="col-sm-4">

                        <p><?php echo date('H:i A', strtotime($model->created_at)); ?></p>
                    </div>
                    <div class="col-sm-4">

                        <p><?php echo $model->orderStatus->name; ?></p>
                    </div>
                </div>

            <?php } ?>
        </div>
    </div>
</div>