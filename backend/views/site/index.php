<?php
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-warning card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">shopping_cart</i>
                        </div>
                        <p class="card-category">Total Orders </p>
                        <h3 class="card-title"><?php echo count($orders); ?>
                            <!--<small>GB</small>-->
                        </h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons text-danger">shopping_cart</i>
                            <a href="../order/orders">View Orders</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-success card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">store</i>
                        </div>
                        <p class="card-category">Revenue</p>
                        <?php $total = array_column($orders, 'total_amount'); ?>
                        <h3 class="card-title">QAR <?php echo number_format(array_sum($total) / 100, 2, ",", "."); ?></h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons">date_range</i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-danger card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">info_outline</i>
                        </div>
                        <p class="card-category">Return Request</p>
                        <h3 class="card-title">75</h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons">local_offer</i> <a href="javascript:;">  View All Request</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-info card-header-icon">
                        <div class="card-icon">
                            <i class="fa fa-user"></i>
                        </div>
                        <p class="card-category">Customers</p>
                        <?php $customers = array_unique(array_filter(array_column($orders, 'user_id'))); ?>
                        <h3 class="card-title"><?= count($customers); ?></h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons">supervisor_account</i> <a href="../users/user">View All User</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card card-chart">
                    <div class="card-header card-header-success">
                        <div class="ct-chart" id="dailySalesChart"></div>
                    </div>
                    <div class="card-body">
                        <h4 class="card-title text-center">Daily Sales</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-chart">
                    <div class="card-header card-header-warning">
                        <div class="ct-chart" id="websiteViewsChart"></div>
                    </div>
                    <div class="card-body">
                        <h4 class="card-title text-center">Newsletter Subscriptions</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-chart">
                    <div class="card-header card-header-danger">
                        <div class="ct-chart" id="completedTasksChart"></div>
                    </div>
                    <div class="card-body">
                        <h4 class="card-title text-center">Completed Orders</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header card-header-warning">
                        <h4 class="card-title">Latest Orders</h4>
                        <!--<p class="card-category">New employees on 15th September, 2016</p>-->
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-hover">
                            <thead class="text-warning">
                            <th>Order ID</th>
                            <th>Order Amount</th>
                            <th style="width: 25% !important;">
                                <table>
                                    <tr>
                                        <td class="text-center" colspan="3"><b>Items</b></td>
                                        <!--<td>How</td>-->
                                        <!--<td>You</td>-->
                                    </tr>
                                    <tr>
                                        <td style="width: 20% !important">Name</td>
                                        <td style="width: 20% !important">SKU</td>
                                        <td style="width: 20% !important">Quantity</td>
                                        <td style="width: 20% !important">Price</td>
                                        <td style="width: 20% !important">Total</td>
                                    </tr>

                                    </td>
                                    </tr>
                                </table>
                            </th>
                            <th>Status</th>
                            </thead>
                            <tbody>
                                <?php if ($orders != NULL) { ?>
                                    <?php
                                    foreach ($orders as $model) {
                                        $getOrderProducts = common\models\OrderProducts::find()->where(['order_id' => $model->id])->all();
                                        ?>
                                        <tr>
                                            <td><?= $model->id; ?></td>
                                            <td>Qar <?= $model->total_amount; ?></td>
                                            <td>
                                                <table>
                                                    <?php if ($getOrderProducts != NULL) { ?>
                                                        <?php foreach ($getOrderProducts as $getOrderProduct) { ?>
                                                            <tr>
                                                                <td style="width: 20% !important ;text-align: center">Carrot</td>
                                                                <td style="width: 20% !important ;text-align: center">CAR766</td>
                                                                <td style="width: 20% !important ;text-align: center">2</td>
                                                                <td style="width: 20% !important ;text-align: center">Qar 35</td>
                                                                <td style="width: 20% !important ;text-align: center">Qar 70</td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } ?>


                                                </table>
                                            </td>
                                            <td></td>


                                        </tr>
                                    <?php } ?>
                                <?php } ?>

                                <tr>
                                    <td>2</td>
                                    <td>Minerva Hooper</td>
                                    <td>QAR 23,789</td>
                                    <td>CuraÃ§ao</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Sage Rodriguez</td>
                                    <td>QAR 56,142</td>
                                    <td>Netherlands</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Philip Chaney</td>
                                    <td>QAR 38,735</td>
                                    <td>Korea, South</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>