<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
/**
*
* @package    Material Dashboard Yii2
* @author     CodersEden <hello@coderseden.com>
    * @link       https://www.coderseden.com
    * @copyright  2020 Material Dashboard Yii2 (https://www.coderseden.com)
    * @license    MIT - https://www.coderseden.com
    * @since      1.0
    */
    use yii\helpers\Html;
    use yii\widgets\DetailView;
    ?>
    <div class="content">
        <div class="container-fluid">
            <div class="card ">
                <div class="card-header card-header-primary card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">account_box</i>
                    </div>
                    <h4 class="card-title">
                        <?= Html::encode('User Addresses')?>

                        <div class="pull-right">
                            <?=                             Html::a(Html::tag('b', 'keyboard_arrow_left', ['class' => 'material-icons']), ['index'], [
                            'class' => 'btn btn-xs btn-success btn-round btn-fab',
                            'rel' => "tooltip",
                            'data' => [
                            'placement' => 'bottom',
                            'original-title' => 'Back'
                            ],
                            ])
                            ?>
                            <?=                             Html::a(Html::tag('b', 'create', ['class' => 'material-icons']), ['update', 'id' => $model->id], [
                            'class' => 'btn btn-xs btn-success btn-round btn-fab',
                            'rel' => "tooltip",
                            'data' => [
                            'placement' => 'bottom',
                            'original-title' => 'Edit User'
                            ],
                            ])
                            ?>
                            <?=                             Html::a(Html::tag('b', 'delete', ['class' => 'material-icons']), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-xs btn-danger btn-round btn-fab',
                            'rel' => "tooltip",
                            'onclick' => "return confirm('Are you sure you want to delete this item?')",
                            'data' => [
                            'confirm' => \Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                            'placement' => 'bottom',
                            'original-title' => 'Delete User'
                            ],
                            ])
                            ?>
                        </div>
                    </h4>
                </div>
                <div class="card-body">

                    <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                                'id',
            'user_id',
            'first_name',
            'last_name',
            'country',
            'state',
            'city',
            'streat_address:ntext',
            'postcode',
            'phone_number',
            'default_billing_address',
            'default_shipping_address',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'email:email',
                    ],
                    ]) ?>


                </div>
            </div>
        </div>
    </div>
