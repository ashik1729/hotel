<div class="sidebar" data-color="purple" data-background-color="white" data-image="<?= \Yii::getAlias('@web/img/sidebar-1.jpg'); ?>">
    <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
    -->
    <div class="logo"><a href="https://www.coderseden.com" class="simple-text logo-normal">
            CAPON Franchise
        </a></div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="nav-item active  ">
                <a class="nav-link" href="<?= \yii\helpers\Url::to(['/site/index']); ?>">
                    <i class="material-icons">dashboard</i>
                    <p>Dashboard</p>
                </a>
            </li>

            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#merchants" aria-expanded="false">

                    <i class="material-icons">business</i>

                    <p>Account Settings
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="merchants">
                    <ul class="nav">

                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/users/franchise/view?id=' . Yii::$app->user->identity->id]); ?>">
                                <i class="material-icons">business</i>
                                <span class="sidebar-normal">Profile Settings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/users/merchant/index']); ?>">
                                <i class="material-icons">business</i>
                                <span class="sidebar-normal">Manage Merchants</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#products-services" aria-expanded="false">

                    <i class="material-icons">design_services</i>

                    <p>Products And Service
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="products-services">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/products/products-services/index']); ?>">
                                <i class="material-icons">design_services</i>
                                <span class="sidebar-normal">Product & Services</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/products/category/index']); ?>">
                                <i class="material-icons">design_services</i>
                                <span class="sidebar-normal">Category</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/products/attributes/index']); ?>">
                                <i class="material-icons">label_important</i>

                                <span class="sidebar-normal">Product Attributes</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/products/product-review/index']); ?>">
                                <i class="material-icons">reviews</i>

                                <span class="sidebar-normal">Products Review</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#orders" aria-expanded="false">

                    <i class="material-icons">shopping_cart</i>

                    <p>Manage Orders
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="orders">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/order/orders/index']); ?>">
                                <i class="material-icons">shopping_cart</i>
                                <span class="sidebar-normal">Orders</span>
                            </a>
                        </li>
                        <!--                        <li class="nav-item">
                                                    <a class="nav-link" href="<?= \yii\helpers\Url::to(['returns/index']); ?>">
                                                        <i class="material-icons">reviews</i>

                                                        <span class="sidebar-normal">Returns</span>
                                                    </a>
                                                </li>-->

                    </ul>
                </div>
            </li>


            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#plans" aria-expanded="false">

                    <i class="material-icons">subscriptions</i>


                    <p>My Subscriptions
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="plans">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['plans/index']); ?>">
                                <i class="material-icons">subscriptions</i>
                                <span class="sidebar-normal">My Plans</span>
                            </a>
                        </li>


                    </ul>
                </div>
            </li>

        </ul>
    </div>
</div>