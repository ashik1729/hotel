<?php
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>








<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">account_box</i>
                        </div>
                        <h4 class="card-title">
                            <?= Html::encode($this->title) ?>

                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="material-datatables">
                            <div class="alert alert-danger">
                                <?= nl2br(Html::encode($message)) ?>
                            </div>

                            <p>
                                The above error occurred while the Web server was processing your request.
                            </p>
                            <p>
                                Please contact us if you think this is a server error. Thank you.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

