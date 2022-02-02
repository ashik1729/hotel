<?php

namespace frontend\controllers;

use common\models\Accomodation;
use common\models\AccomodationRequest;
use common\models\Brands;
use common\models\Cars;
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
use common\models\RentalEnquiry;
use common\models\TypeOfCar;
use common\models\Visa;
use common\models\VisaFaq;
use common\models\VisaRequests;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
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
 
        return $this->render('packages');
    }
    public function actionPackageDetails()
    {
        
        return $this->render('package-details');
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
                return  $this->redirect(['visa-details/'.$_GET['can']]);

            }else{
                Yii::$app->session->setFlash('error', "Please Login before making visa request.");
                return $this->redirect(['visa-details/'.$_GET['can']]);


            }
        }

        return $this->render('visa-details', ['visa' => $visa, 'visafaq' => $visafaq,'visaRequest'=>$visaRequest]);
    }
    public function actionIndex()
    {
        return $this->render('index');
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
            'events',
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
                'eventData'=>$eventData,
                'eventRequest'=>$eventRequest
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
                'flgihtRequest'=>$flgihtRequest
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
