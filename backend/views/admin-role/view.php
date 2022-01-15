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
                        <?= Html::encode('Admin Roles')?>
                        <div class="pull-right">
                            <a class="btn btn-xs btn-success btn-round btn-fab" href="/advanced-new/admin/gii" rel="tooltip" data-placement="bottom" data-original-title="Back"><b class="material-icons">keyboard_arrow_left</b></a>                            <a class="btn btn-xs btn-success btn-round btn-fab" href="/advanced-new/admin/gii/default/update" rel="tooltip" data-placement="bottom" data-original-title="Edit User"><b class="material-icons">create</b></a>                            <a class="btn btn-xs btn-danger btn-round btn-fab" href="/advanced-new/admin/gii/default/delete" rel="tooltip" data-confirm="Are you sure you want to delete this item?" data-method="post" data-placement="bottom" data-original-title="Delete User"><b class="material-icons">delete</b></a>                        </div>
                    </h4>
                </div>
                <div class="card-body">

                    <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                                'id',
            'role_name',
            'status',
            'created_at',
            'updated_at',
                    ],
                    ]) ?>


                </div>
            </div>
        </div>
    </div>
