<?php

namespace frontend\controllers;

use common\models\Accomodation;
use common\models\AccomodationRequest;
use common\models\BookingTravellers;
use common\models\Brands;
use common\models\Cars;
use common\models\Cart;
use common\models\Category;
use common\models\CmsContent;
use common\models\Enquiry;
use common\models\EventRequest;
use common\models\Events;
use common\models\FlightRequest;
use kartik\widgets\FileInput;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\PackageDestination;
use common\models\PackagesDate;
use common\models\PackagesPrice;
use common\models\ProductReview;
use common\models\ProductsServices;
use common\models\RentalEnquiry;
use common\models\TypeOfCar;
use common\models\Visa;
use common\models\VisaFaq;
use common\models\VisaRequests;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use Mpdf\Output\Destination;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

//use Imagine\Imagick\Image;

/**
 * Site controller
 */
class SiteController extends Controller
{
    // public $enableCsrfValidation = false;

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
            ],
        ];
    }


    public function actionError()
    {

        $this->layout = 'defaultLayoutName';
        //rest of the code goes here
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionPackages()
    {
        $query = ProductsServices::find()->where(['status' => 1]);
        if (isset($_REQUEST['search_key']) && $_REQUEST['search_key'] != "") {
            $query->andWhere(['LIKE', 'package_title', $_REQUEST['search_key']]);
            $query->orWhere(['LIKE', 'short_description_en', $_REQUEST['search_key']]);
            $query->orWhere(['LIKE', 'long_description_en', $_REQUEST['search_key']]);
        }
        if (isset($_REQUEST['destination']) && $_REQUEST['destination'] != "") {
            $query->andWhere(['destination' => $_REQUEST['destination']]);
        }
        if (isset($_REQUEST['category']) && $_REQUEST['category'] != "") {
            $query->andWhere(['category_id' => $_REQUEST['category']]);
        }
        $packages = $query->all();
        $destinations = PackageDestination::find()->where(['status' => 1])->all();
        $cateory = Category::find()->where(['status' => 1])->all();
        $model = CmsContent::findOne(['page_id' => 'packages']);
        return $this->render(
            'packages',
            [
                'model' => $model,
                'packages' => $packages,
                'destinations' => $destinations,
                'cateory' => $cateory
            ]
        );
    }
    public function actionPackageDetails()
    {
        $model = ProductsServices::findOne(['canonical_name' => $_GET['can'], 'status' => 1]);
        $packageReviews = ProductReview::find()->where(['review_type' => 1, 'review_for_id' => $model->id, 'approvel' => 1])->all();
        return $this->render('package-details', ['model' => $model, 'packageReviews' => $packageReviews]);
    }
    public function actionCart()
    {
        $models = Cart::find()->where(['user_id' => Yii::$app->user->id, 'status' => 1])->all();

        if (!Yii::$app->user->isGuest) {
            return $this->render('cart', ['models' => $models]);
        } else {
            return  $this->redirect(['index']);
        }
    }
    public function actionDeleteCart($id)
    {
        if (!Yii::$app->user->isGuest) {
            $getCart = Cart::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);
            if ($getCart != NULL) {
                if ($getCart->delete()) {
                    Yii::$app->session->setFlash('success', "Package Deleted Successfully.");
                } else {
                    Yii::$app->session->setFlash('error', "Following Error While Delete to your package." . json_encode($getCart->errors));
                }
            } else {
                Yii::$app->session->setFlash('error', "Invlaid cart Info");
            }

            return  $this->redirect(['cart']);
        } else {
            return  $this->redirect(['index']);
        }
    }
    public function actionBookPackage()
    {
        $model = ProductsServices::findOne(['canonical_name' => $_GET['can'], 'status' => 1]);
        $cart = new Cart();
        if ($cart->load(Yii::$app->request->post())) {
            if (!Yii::$app->user->isGuest) {
                $checkCart = Cart::find()->where(['user_id' => Yii::$app->user->id, 'date' => $_POST['Cart']['date'], 'product_id' => $model->id])->one();
                if ($checkCart != NULL) {
                    $cart = $checkCart;
                }
                $cart->id = strtoupper(uniqid('HCCA'));
                $cart->user_id = Yii::$app->user->id;
                $cart->product_id = $model->id;
                $cart->quantity = 1;
                $cart->status = 1;
                if ($cart->save()) {
                    Yii::$app->session->setFlash('success', "Package Added  Successfully.");
                    return  $this->redirect(['book-package-details/' . $cart->id]);
                } else {
                    Yii::$app->session->setFlash('error', "Following Error While Adding to your package." . json_encode($cart->errors));
                    return  $this->redirect(['book-package/' . $_GET['can']]);
                }
            } else {
                Yii::$app->session->setFlash('error', "Please Login before making Booking request.");
                return  $this->redirect(['book-package/' . $_GET['can']]);
            }
        }
        return $this->render('book-package', ['model' => $model, 'cart' => $cart]);
    }
    public function actionCalculatePrice()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $no_adults = $_POST['no_adults'];
            $package_id = $_POST['package_id'];
            $date = $_POST['date'];
            $package = ProductsServices::find()->where(['id' => $package_id])->one();
            $result = [];
            $result['status'] = 400;
            if ($package != NULL) {
                $packageDate = PackagesDate::find()->where(['package_date' => $date, 'package_id' => $package_id])->one();
                if ($packageDate != NULL) {
                    $packagePrice = \common\models\PackagesPrice::find()->where(['package_date_id' => $packageDate])->andWhere("min_person <= '" . $no_adults . "' AND max_person >= '" . $no_adults . "' ")->one();
                    if ($packagePrice != NULL) {
                        $totalPrice = $no_adults * $packagePrice->price;
                        $result['price'] = $packagePrice->price;
                        $result['subtotal'] = "AED " . $totalPrice;
                        $result['total'] = "AED " . $totalPrice;
                        $result['status'] = 200;
                        $result['message'] = "success";
                    } else {
                        $result['status'] = 201;
                        $result['message'] = "Empty Price";
                    }
                } else {
                    $result['status'] = 202;
                    $result['message'] = "Empty Package Date";
                }
            } else {
                $result['status'] = 202;
                $result['message'] = "Empty Package";
            }
            echo json_encode($result);
            exit;
        }
    }
    public function CalculatePrice($no_adults, $package_id, $date)
    {

        $package = ProductsServices::find()->where(['id' => $package_id])->one();
        if ($package != NULL) {
            $packageDate = PackagesDate::find()->where(['package_date' => $date, 'package_id' => $package_id])->one();
            if ($packageDate != NULL) {
                $packagePrice = \common\models\PackagesPrice::find()->where(['package_date_id' => $packageDate])->andWhere("min_person <= '" . $no_adults . "' AND max_person >= '" . $no_adults . "' ")->one();
                if ($packagePrice != NULL) {
                
                    return $packagePrice->price;
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
    public function actionBookPackageDetails()
    {

        $booking_travellers = new BookingTravellers();
        $cart =  Cart::findOne(['id' => $_GET['cart_id'], 'user_id' => Yii::$app->user->id]);
        if ($cart != NULL) {
            $model = ProductsServices::findOne(['id' => $cart->product_id, 'status' => 1]);
            if ($booking_travellers->load(Yii::$app->request->post())) {
                if (!Yii::$app->user->isGuest) {
                    if (isset($_POST['BookingTravellers']['first_name'])) {
                        $transaction = Yii::$app->db->beginTransaction();
                        $count = count($_POST['BookingTravellers']['first_name']);
                        if ($count > 0) {
                            $errors = [];
                            for ($i = 0; $i < $count; $i++) {
                                $booking_travellers = new BookingTravellers();
                                $booking_travellers->user_id = Yii::$app->user->id;
                                $booking_travellers->cart_id = $_GET['cart_id'];
                                $booking_travellers->first_name = $_POST['BookingTravellers']['first_name'][$i];
                                $booking_travellers->last_name = $_POST['BookingTravellers']['last_name'][$i];
                                $booking_travellers->status = 1;
                                if ($booking_travellers->save()) {
                                } else {
                                    $errors[] = $booking_travellers->errors;
                                }
                            }
                            if ($errors != NULL) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', "Following Error While Adding to your package." . json_encode($errors));
                                return  $this->redirect(['book-package-details/' . $_GET['cart_id']]);
                            } else {
                                if ($cart->load(Yii::$app->request->post())) {
                                    $cart->price = $this->CalculatePrice($cart->no_adults, $model->id, $cart->date);
                                    if ($cart->save()) {
                                        $transaction->commit();
                                        Yii::$app->session->setFlash('success', "Booking Updated Successfully  Successfully.");
                                        return  $this->redirect(['cart']);
                                    } else {
                                        $transaction->rollBack();
                                        Yii::$app->session->setFlash('error', "Following Error While updating to your package." . json_encode($cart->errors));
                                        return  $this->redirect(['book-package-details/' . $_GET['cart_id']]);
                                    }
                                } else {
                                    $transaction->rollBack();
                                    Yii::$app->session->setFlash('error', "Invalid Cart Details");
                                    return  $this->redirect(['book-package-details/' . $_GET['cart_id']]);
                                }
                            }
                        }
                    }
                } else {
                    Yii::$app->session->setFlash('error', "Please Login before making Booking request.");
                    return  $this->redirect(['book-package/' . $_GET['can']]);
                }
            }
            return $this->render('book-package-details', ['booking_travellers' => $booking_travellers, 'cart' => $cart, 'model' => $model]);
        } else {
            return  $this->redirect(['error']);
        }
    }
    public function actionVisa()
    {
        $model = CmsContent::findOne(['page_id' => 'visa']);
        $visas = Visa::find()->where(['status' => 1])->all();

        return $this->render('visa', ['model' => $model, 'visas' => $visas]);
    }
    public function actionVisaDetails()
    {
        $visa = Visa::findOne(['can_name' => $_GET['can'], 'status' => 1]);
        $visafaq = VisaFaq::find()->where(['visa_id' => $visa->id, 'status' => 1])->all();
        $visaRequest = new VisaRequests();
        if ($visaRequest->load(Yii::$app->request->post())) {
            if (!Yii::$app->user->isGuest) {

                $visaRequest->status = 1;
                $visaRequest->user_id = Yii::$app->user->id;
                if ($visaRequest->save()) {
                    Yii::$app->session->setFlash('success', "Visa Enquiry Sent Successfully.");
                } else {
                    Yii::$app->session->setFlash('error', "Following Error While Senting your Enquiry." . json_encode($visaRequest->errors));
                }
                return  $this->redirect(['visa-details/' . $_GET['can']]);
            } else {
                Yii::$app->session->setFlash('error', "Please Login before making visa request.");
                return $this->redirect(['visa-details/' . $_GET['can']]);
            }
        }

        return $this->render('visa-details', ['visa' => $visa, 'visafaq' => $visafaq, 'visaRequest' => $visaRequest]);
    }
    public function actionIndex()
    {
        $packages = ProductsServices::find()->where(['status' => 1])->all();
        $reviews = ProductReview::find()->where(['approvel' => 1])->all();
        return $this->render('index', ['packages' => $packages, 'reviews' => $reviews]);
    }
    public function actionAccomodationGallery()
    {
        $model = Accomodation::findOne(['can_name' => $_GET['can'], 'status' => 1]);
        return $this->render('accomodation-gallery', [
            'model' => $model
        ]);
    }
    public function actionAccomodation()
    {
        $model = CmsContent::findOne(['page_id' => 'accomodation']);
        $enquiry = new Enquiry();
        $accommodation = new AccomodationRequest();
        $accommodationData = Accomodation::find()->where(['status' => 1])->all();
        if ($enquiry->load(Yii::$app->request->post())) {
            $enquiry->status = 1;
            if ($enquiry->save()) {
                Yii::$app->session->setFlash('success', "Enquiry Sent Successfully.");
            } else {
                Yii::$app->session->setFlash('error', "Following Error While Senting your Enquiry." . json_encode($enquiry->errors));
            }
            return $this->redirect(['accomodation']);
        }
        if ($accommodation->load(Yii::$app->request->post())) {
            $accommodation->status = 1;
            if ($accommodation->save()) {
                Yii::$app->session->setFlash('success', "Accomodation request Sent Successfully.");
            } else {
                Yii::$app->session->setFlash('error', "Following Error While Senting ccomodation request." . json_encode($accommodation->errors));
            }
            return $this->redirect(['accomodation']);
        }
        return $this->render('accomodation', [
            'model' => $model,
            'accommodation' => $accommodation,
            'enquiry' => $enquiry,
            'accommodationData' => $accommodationData
        ]);
    }
    public function actionContactUs()
    {
        $enquiry = new Enquiry();
        $model = CmsContent::findOne(['page_id' => 'contact-us']);
        if ($enquiry->load(Yii::$app->request->post())) {
            $enquiry->status = 1;
            if ($enquiry->save()) {
                Yii::$app->session->setFlash('success', "Enquiry Sent Successfully.");
            } else {
                Yii::$app->session->setFlash('error', "Following Error While Senting your Enquiry." . json_encode($enquiry->errors));
            }
            return $this->redirect(['contact-us']);
        }
        return $this->render('contact-us', [
            'model' => $model,
            'enquiry' => $enquiry
        ]);
    }

    public function actionAboutUs()
    {
        $model = CmsContent::findOne(['page_id' => 'about-us']);
        return $this->render(
            'about-us',
            [
                'model' => $model
            ]
        );
    }
    public function actionEventGallery()
    {
        $model = Events::findOne(['can_name' => $_GET['can'], 'status' => 1]);
        return $this->render('event-gallery', [
            'model' => $model
        ]);
    }
    public function actionEvents()
    {
        $model = CmsContent::findOne(['page_id' => 'events']);
        $eventData = Events::find()->where(['status' => 1])->all();
        $eventRequest = new EventRequest();

        if ($eventRequest->load(Yii::$app->request->post())) {
            $eventRequest->status = 1;
            if ($eventRequest->save()) {
                Yii::$app->session->setFlash('success', "Event request Sent Successfully.");
            } else {
                Yii::$app->session->setFlash('error', "Following Error While Senting ccomodation request." . json_encode($eventRequest->errors));
            }
            return $this->redirect(['events']);
        }
        return $this->render(
            'events',
            [
                'model' => $model,
                'eventData' => $eventData,
                'eventRequest' => $eventRequest
            ]
        );
    }
    public function actionFlightTickets()
    {
        $model = CmsContent::findOne(['page_id' => 'flight-tickets']);
        $flgihtRequest = new FlightRequest();

        if ($flgihtRequest->load(Yii::$app->request->post())) {
            $flgihtRequest->status = 1;
            if ($flgihtRequest->save()) {
                Yii::$app->session->setFlash('success', "Flight Booking request Sent Successfully.");
            } else {
                Yii::$app->session->setFlash('error', "Following Error While Senting Flight Booking request." . json_encode($flgihtRequest->errors));
            }
            return $this->redirect(['flight-tickets']);
        }
        return $this->render(
            'flight-tickets',
            [
                'model' => $model,
                'flgihtRequest' => $flgihtRequest
            ]
        );
    }
    public function actionRentCarDetails()
    {
        $car = Cars::findOne(['can_name' => $_GET['can'], 'status' => 1]);
        $rentEnquiry = new RentalEnquiry();
        if ($rentEnquiry->load(Yii::$app->request->post())) {
            $rentEnquiry->status = 1;
            if ($rentEnquiry->save()) {
                Yii::$app->session->setFlash('success', "Rental Enquiry Sent Successfully.");
            } else {
                Yii::$app->session->setFlash('error', "Following Error While Senting your Enquiry." . json_encode($rentEnquiry->errors));
            }
            return $this->redirect(['rent-car-details/' . $_GET['can']]);
        }
        return $this->render('rent-car-details', [
            'car' => $car,
            'rentEnquiry' => $rentEnquiry
        ]);
    }
    public function actionRentCar()
    {

        $model = CmsContent::findOne(['page_id' => 'rent-a-car', 'status' => 1]);
        $typeOfCars =  TypeOfCar::find()->where(['status' => 1])->all();
        $brands =  Brands::find()->where(['status' => 1])->all();
        $carquery = Cars::find()->where(['status' => 1]);
        $rentEnquiry = new RentalEnquiry();
        if (isset($_GET['type_of_car']) && $_GET['type_of_car'] != "") {
            $carquery->andWhere(['type_of_car' => $_GET['type_of_car']]);
        }
        if (isset($_GET['brand']) && $_GET['brand'] != "") {
            $carquery->andWhere(['brand' => $_GET['brand']]);
        }
        if (isset($_GET['sorting']) && $_GET['sorting'] != "") {
            if ($_GET['sorting'] == 1) {
                $carquery->orderBy([
                    'model_year' => SORT_DESC
                ]);
            } else {
                $carquery->orderBy([
                    'model_year' => SORT_ASC
                ]);
            }
        } else {
            $carquery->orderBy([
                'model_year' => SORT_DESC
            ]);
        }

        $cars = $carquery->all();
        if ($rentEnquiry->load(Yii::$app->request->post())) {
            $rentEnquiry->status = 1;
            if ($rentEnquiry->save()) {
                Yii::$app->session->setFlash('success', "Rental Enquiry Sent Successfully.");
            } else {
                Yii::$app->session->setFlash('error', "Following Error While Senting your Enquiry." . json_encode($rentEnquiry->errors));
            }
            return $this->redirect(['rent-car']);
        }
        return $this->render('rent-car', [
            'model' => $model,
            'typeOfCars' => $typeOfCars,
            'brands' => $brands,
            'cars' => $cars,
            'rentEnquiry' => $rentEnquiry
        ]);
    }
}
