<div class="sidebar" data-color="purple" data-background-color="white" data-image="<?= \Yii::getAlias('@web/img/sidebar-1.jpg'); ?>">

    <div class="logo"><a href="https://www.coderseden.com" class="simple-text logo-normal">
            HCCA- Admin
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

                    <p>Packages
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="products-services">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/products/products-services/index']); ?>">
                                <i class="material-icons">design_services</i>
                                <span class="sidebar-normal">Packages</span>
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
            <li class="nav-item">
                <a class="nav-link" href="<?= \yii\helpers\Url::to(['/marketing/banner/index']); ?>">
                    <i class="material-icons">shopping_cart</i>
                    <span class="sidebar-normal">Banners</span>
                </a>
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
            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#visa" aria-expanded="false">

                    <i class="material-icons">shopping_cart</i>

                    <p>Visa
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="visa">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/visa-requests/index']); ?>">
                                <i class="material-icons">image</i>

                                <span class="sidebar-normal">Visa Requests</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/visa/index']); ?>">
                                <i class="material-icons">image</i>

                                <span class="sidebar-normal">Visa Contents</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/visa-option/index']); ?>">
                                <i class="material-icons">image</i>

                                <span class="sidebar-normal">Visa Options</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/processing-type/index']); ?>">
                                <i class="material-icons">image</i>

                                <span class="sidebar-normal">Processing Type</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/visa-faq/index']); ?>">
                                <i class="material-icons">image</i>

                                <span class="sidebar-normal">Visa Faq</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>
            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#images" aria-expanded="false">

                    <i class="material-icons">image</i>

                    <p>Cars
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="images">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/cars/index']); ?>">
                                <i class="material-icons">shopping_cart</i>
                                <span class="sidebar-normal">Manage Cars</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/rental-enquiry/index']); ?>">
                                <i class="material-icons">shopping_cart</i>
                                <span class="sidebar-normal">Rental Requests</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#cms-content" aria-expanded="false">

                    <i class="material-icons">image</i>

                    <p>Content
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="cms-content">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/cms-content/index']); ?>">
                                <i class="material-icons">notifications</i>
                                <span class="sidebar-normal">Manage Pages</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/cms-data/index']); ?>">
                                <i class="material-icons">notifications</i>
                                <span class="sidebar-normal">Page Contents</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>
            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#accommodation" aria-expanded="false">

                    <i class="material-icons">image</i>

                    <p>Accomodation
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="accommodation">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/accomodation/index']); ?>">
                                <i class="material-icons">notifications</i>
                                <span class="sidebar-normal">Accommodation List</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/accomodation-request/index']); ?>">
                                <i class="material-icons">notifications</i>
                                <span class="sidebar-normal">Accommodation Request</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>
            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#events" aria-expanded="false">

                    <i class="material-icons">image</i>

                    <p>Events
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="events">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/events/index']); ?>">
                                <i class="material-icons">shopping_cart</i>
                                <span class="sidebar-normal">Events</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/event-request/index']); ?>">
                                <i class="material-icons">shopping_cart</i>
                                <span class="sidebar-normal">Event Requests</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= \yii\helpers\Url::to(['/flight-request/index']); ?>">
                    <i class="material-icons">shopping_cart</i>
                    <span class="sidebar-normal">Flight Requests</span>
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#masters" aria-expanded="false">

                    <i class="material-icons">shopping_cart</i>

                    <p>Master Datas
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="masters">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/brands/index']); ?>">
                                <i class="material-icons">shopping_cart</i>
                                <span class="sidebar-normal">Car Brands</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/car-option-master/index']); ?>">
                                <i class="material-icons">shopping_cart</i>
                                <span class="sidebar-normal">Car Options</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/documents-master/index']); ?>">
                                <i class="material-icons">shopping_cart</i>
                                <span class="sidebar-normal">Document Required Master</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/extras-master/index']); ?>">
                                <i class="material-icons">shopping_cart</i>
                                <span class="sidebar-normal">Extras Master</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/general-information-master/index']); ?>">
                                <i class="material-icons">shopping_cart</i>
                                <span class="sidebar-normal">General Inforamtion Master</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/type-of-car/index']); ?>">
                                <i class="material-icons">shopping_cart</i>
                                <span class="sidebar-normal">Type Of Cars</span>
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
                            <a class="nav-link" href="<?= \yii\helpers\Url::to(['/order-status/index']); ?>">
                                <i class="material-icons">history</i>

                                <span class="sidebar-normal">Order Status</span>
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


                    </ul>
                </div>
            </li>




        </ul>
    </div>
</div>