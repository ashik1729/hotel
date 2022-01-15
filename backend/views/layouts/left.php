<div class="sidebar" data-color="purple" data-background-color="white" data-image="<?= \Yii::getAlias('@web/img/sidebar-1.jpg'); ?>">

    <div class="logo"><a href="https://www.coderseden.com" class="simple-text logo-normal">
            CAPON- Admin
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
                <a class="nav-link" data-toggle="collapse" href="#yii2Example" aria-expanded="false">

                    <i class="material-icons">supervised_user_circle</i>

                    <p>Admin
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="yii2Example">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/user-admin/index']); ?>">
                                <i class="material-icons">person</i>
                                <span class="sidebar-normal"> Admin Users</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/admin-role/index']); ?>">
                                <i class="material-icons">vpn_key</i>

                                <span class="sidebar-normal"> Admin Roles</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#fanchesi" aria-expanded="false">

                    <i class="material-icons">account_balance</i>

                    <p>Franchise
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="fanchesi">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/users/franchise/index']); ?>">
                                <i class="material-icons">account_balance</i>
                                <span class="sidebar-normal">Franchise</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>
            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#merchants" aria-expanded="false">

                    <i class="material-icons">business</i>

                    <p>Merchants
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="merchants">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/users/merchant/index']); ?>">
                                <i class="material-icons">business</i>
                                <span class="sidebar-normal">Merchants</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/users/merchant-category/index']); ?>">
                                <i class="material-icons">business</i>
                                <span class="sidebar-normal">Merchant Category</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/users/features-list/index']); ?>">
                                <i class="material-icons">business</i>
                                <span class="sidebar-normal">Features List</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/users/merchant-feature-list/index']); ?>">
                                <i class="material-icons">business</i>
                                <span class="sidebar-normal">Merchant Feature List </span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>
            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#users" aria-expanded="false">

                    <i class="material-icons">business</i>

                    <p>Users
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="users">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/users/user/index']); ?>">
                                <i class="material-icons">business</i>
                                <span class="sidebar-normal">Users</span>
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
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/products/discounts/index']); ?>">
                                <i class="material-icons">reviews</i>

                                <span class="sidebar-normal">Discounts</span>
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
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/order/order-status/index']); ?>">
                                <i class="material-icons">shopping_cart</i>
                                <span class="sidebar-normal">Order Status</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/order/returns/index']); ?>">
                                <i class="material-icons">reviews</i>

                                <span class="sidebar-normal">Returns</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>
            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#variables" aria-expanded="false">

                    <i class="material-icons">shopping_cart</i>

                    <p>Manage Variables
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="variables">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/variables/index']); ?>">
                                <i class="material-icons">shopping_cart</i>
                                <span class="sidebar-normal">Manage Variables</span>
                            </a>
                        </li>


                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= \yii\helpers\Url::to(['/events/index']); ?>">
                    <i class="material-icons">image</i>

                    <span class="sidebar-normal">Events</span>
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#images" aria-expanded="false">

                    <i class="material-icons">image</i>

                    <p>File Management
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="images">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/filemanagement/image-type/index']); ?>">
                                <i class="material-icons">shopping_cart</i>
                                <span class="sidebar-normal">Image Type</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/filemanagement/image-assets/index']); ?>">
                                <i class="material-icons">shopping_cart</i>
                                <span class="sidebar-normal">Image Assets</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#marketing" aria-expanded="false">

                    <i class="material-icons">image</i>

                    <p>Marketing
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="marketing">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/marketing/marketing-notification/index']); ?>">
                                <i class="material-icons">notifications</i>
                                <span class="sidebar-normal">Notifications</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/marketing/notification-type/index']); ?>">
                                <i class="material-icons">notifications</i>
                                <span class="sidebar-normal">Notification Type</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/marketing/promotional-campaign/index']); ?>">
                                <i class="material-icons">shopping_cart</i>
                                <span class="sidebar-normal">Promotional Plans</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/marketing/banner/index']); ?>">
                                <i class="material-icons">shopping_cart</i>
                                <span class="sidebar-normal">Promotional Banners</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#configuration" aria-expanded="false">

                    <i class="material-icons">manage_accounts</i>

                    <p>Configuration
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="configuration">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/masters/user-group/index']); ?>">
                                <i class="material-icons">groups</i>
                                <span class="sidebar-normal">User Groups</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/mobile-strings/index']); ?>">
                                <i class="material-icons">add_location</i>
                                <span class="sidebar-normal">Localaised Strings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/error-code/index']); ?>">
                                <i class="material-icons">add_location</i>
                                <span class="sidebar-normal">Error Code</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/business-category/index']); ?>">
                                <i class="material-icons">add_location</i>
                                <span class="sidebar-normal">Business Type</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/masters/shipment-methods/index']); ?>">
                                <i class="material-icons">add_location</i>
                                <span class="sidebar-normal">Shipping Methods</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/country/index']); ?>">
                                <i class="material-icons">add_location</i>
                                <span class="sidebar-normal">Country</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/states/index']); ?>">
                                <i class="material-icons">vpn_key</i>

                                <span class="sidebar-normal">States</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/masters/city/index']); ?>">
                                <i class="material-icons">vpn_key</i>

                                <span class="sidebar-normal">City</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/masters/area/index']); ?>">
                                <i class="material-icons">vpn_key</i>

                                <span class="sidebar-normal">Area</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/currency/index']); ?>">
                                <i class="material-icons">attach_money</i>

                                <span class="sidebar-normal">Currency</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/language/index']); ?>">
                                <i class="material-icons">language</i>

                                <span class="sidebar-normal">Language</span>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/cms-content/index']); ?>">
                                <i class="material-icons">image</i>

                                <span class="sidebar-normal">Contents</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/system-configuration/index']); ?>">
                                <i class="material-icons">admin_panel_settings</i>

                                <span class="sidebar-normal">System Configuration</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/system-configuration/index']); ?>">
                                <i class="material-icons">settings</i>

                                <span class="sidebar-normal">Settings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/order-status/index']); ?>">
                                <i class="material-icons">history</i>

                                <span class="sidebar-normal">Order Status</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/tax-class/index']); ?>">
                                <i class="material-icons">history</i>

                                <span class="sidebar-normal">Tax Class</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/weight-class/index']); ?>">
                                <i class="material-icons">history</i>

                                <span class="sidebar-normal">Weight Class</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/payment-options/index']); ?>">
                                <i class="material-icons">history</i>

                                <span class="sidebar-normal">Payment Options</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>



            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#plans" aria-expanded="false">

                    <i class="material-icons">subscriptions</i>

                    <p>Plans
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="plans">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/plans/index']); ?>">
                                <i class="material-icons">subscriptions</i>
                                <span class="sidebar-normal">Plans</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/periods/index']); ?>">
                                <i class="material-icons">date_range</i>

                                <span class="sidebar-normal">Plan Periods</span>
                            </a>
                        </li>

                    </ul>
                </div>
            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#yii2Examplechat" aria-expanded="false">

                    <i class="material-icons">message</i>

                    <p>Support
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="yii2Examplechat">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/support-tickets/index']); ?>">
                                <i class="material-icons">message</i>
                                <span class="sidebar-normal"> Tickets</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>
            </li>

        </ul>
    </div>
</div>