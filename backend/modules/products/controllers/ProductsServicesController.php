<?php

namespace backend\modules\products\controllers;

use Yii;
use common\models\ProductsServices;
use common\models\ProductsServicesSearch;
use common\models\PackagesDate;
use common\models\PackagesPrice;
use Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\helpers\Json;
use yii\filters\Cors;
use yii\web\UploadedFile;

/**
 * ProductsServicesController implements the CRUD actions for ProductsServices model.
 */
class ProductsServicesController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {

        $tbl_name = 'ProductsServices';
        $get_rules_list = \common\models\AdminRoleList::find()->where(['controller' => $tbl_name . 'Controller'])->all();
        $get_rules = [];
        $route = strtolower(preg_replace('~(?=[A-Z])(?!\A)~', '-', $tbl_name));
        $rule_list = [];
        $action[] = "error";
        if ($get_rules_list != NULL) {
            foreach ($get_rules_list as $get_rules_li) {
                $get_rules = \common\models\AdminRoleLocation::find()->where(['role_id' => Yii::$app->user->identity->role, 'role_list_id' => $get_rules_li->id])->all();
                if ($get_rules != NULL) {
                    foreach ($get_rules as $get_rule) {
                        $action[] = $get_rule->location->action;
                    }
                }
            }
        }
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => $action,
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'delete' => ['POST'],
                ],
            ],
        ];
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function init() {
        parent::init();
        if (Yii::$app->user->isGuest) {
            return $this->redirect(yii::$app->request->baseUrl . '/site/login');
        }
    }

    /**
     * Lists all ProductsServices models.
     * @return mixed
     */
    public function actionIndex() {

        $searchModel     = new ProductsServicesSearch();
        $package_model   = new PackagesDate();
        $pkg_price_model = new PackagesPrice();
        $dataProvider  = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'package_model' => $package_model,
                    'searchModel' => $searchModel,
                    'pkg_price_model' => $pkg_price_model,
                    'dataProvider' => $dataProvider,
        ]);
    }

   
    public function actionAddCategory() {
        if (isset($_POST['parentcat'])) {
            $parentcat = $_POST['parentcat'];
        }
        if (isset($_POST['cat_name'])) {
            $cat_name = $_POST['cat_name'];
        }
        $category = \common\models\Category::find()->where(['parent' => $parentcat, 'category_name' => $cat_name])->one();
        $model = new \common\models\Category();
        if ($category == NULL) {
            $canon_name = strtolower($cat_name);
            $canonical_name = str_replace(' ', '-', $canon_name); // Replaces all spaces with hyphens.
            $canonical_name = preg_replace('/[^A-Za-z0-9\-]/', '', $canonical_name); // Removes special chars.
            $model->canonical_name = preg_replace('/-+/', '-', $canonical_name);
            $model->search_tag = "";
            $model->gallery = "";
            $model->image = "";
            $model->parent = $parentcat;
            $model->category_name = $cat_name;
            $model->description = $cat_name;
            $model->created_by = yii::$app->user->identity->id;
            $model->updated_by = yii::$app->user->identity->id;
            $model->status = 1;
            if ($model->save()) {
                $array['status'] = 200;
                $array['error'] = '';
                $array['message'] = 'Category Add Successfully.';
            } else {
                $array['status'] = 201;
                $array['error'] = $model->errors;
                $array['message'] = 'Form Error Found';
            }
        } else {
            $array['status'] = 300;
            $array['message'] = 'Category Already Exist.';
            $array['error'] = 'Category Already Exist.';
        }

        echo json_encode($array);
        exit;
    }

   
    /**
     * Displays a single ProductsServices model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {

        $model = $this->findModel($id);
        $package = [];
        $package_date_model   = new PackagesDate();
        $pkg_price_model      = new PackagesPrice();
        $package_model        = new ProductsServices();
        $package_date         = \common\models\PackagesDate::find()->where(['package_id' => $id])->all();
        if(!empty($package_date)){
            foreach($package_date as $pkg_date) {
                $package_price = \common\models\PackagesPrice::find()->where(['package_id' => $id, 'package_date_id' => $pkg_date->id])->all();
                if(!empty($package_price)) {
                    foreach($package_price as $package_price) {
                        $package['pkg_date'] = $pkg_date['package_date'];
                        $package['price'][] = $package_price;
                    }

                }

            }

        }
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'package' => $package
        ]);
    }

    /**
     * Creates a new ProductsServices model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new ProductsServices();
        $modelcat = new \common\models\Category();
        $product_attribute = new \common\models\ProductAttributesValue();
        $attribute = new \common\models\Attributes();
        $languages = \common\models\Language::find()->where(['status' => 1])->all();
        $implode_search_tag = "";
        $related_products   = "";
       
        if ($model->load(Yii::$app->request->post())) {
           //echo '<pre/>';print_r(Yii::$app->request->post());exit;
           if(!empty($model->category->category_name)) {
               $cat_name = $model->category->category_name;

           } else {
                $cat_name =  "";
           }
            $canon_name = strtolower($model->package_title  . ' ' . $cat_name);
            $canonical_name = str_replace(' ', '-', $canon_name); // Replaces all spaces with hyphens.
            $canonical_name = preg_replace('/[^A-Za-z0-9\-]/', '', $canonical_name); // Removes special chars.
            $model->canonical_name = preg_replace('/-+/', '-', $canonical_name);
            $model->created_by = yii::$app->user->identity->id;
            $model->updated_by = yii::$app->user->identity->id;
            $model->status = 1;
           // $model->title = $model->package_title ;
            $file = UploadedFile::getInstance($model, 'image');
            $banner_image = UploadedFile::getInstance($model, 'banner_image');
            $gallery = UploadedFile::getInstances($model, 'gallery');
            echo '<pre/>';print_r($gallery);
            $name = md5(microtime());
            $profile_name = 'image' . $name;
            $banner_name = 'banner' . $name;
            if ($file) {
                $model->image = $profile_name . '.' . $file->extension;
            }
            if ($banner_image) {
                $model->banner_image = $banner_name . '.' . $banner_image->extension;
            }
            
             $model->gallery = "";
            $transaction = Yii::$app->db->beginTransaction();
            try {

                
                if ($model->save()) {
                  //  $model->sku = Yii::$app->params['sku_prefix'] . 'M' . $model->merchant_id . 'PS' . $model->id;
                    $model->save(FALSE);
                    if ($file) {
                        if (!$model->uploadFile($file, $profile_name, 'products/' . base64_encode($model->id) . '/image')) {
                            $transaction->rollBack();
                        }
                    }
                    if ($banner_image) {
                        $model->uploadBanner($banner_image, $banner_name, 'product-banner/' . $model->id . '/image');
                    }
                    if ($gallery != NULL) {
                        if (!$model->uploadMultipleImage($gallery, $model->id, $name, 'products/' . base64_encode($model->id) . '/gallery')) {
                            $transaction->rollBack();
                        }
                    } 
                    if (Yii::$app->request->post('ProductAttributesValue') && Yii::$app->request->post('ProductAttributesValue') != NULL) {
                        $manageAttribute = $this->newmanageAttribute($model, Yii::$app->request->post('ProductAttributesValue'));
                        if ($manageAttribute['status'] == 411) {
                            $product_attribute->addError('error', $manageAttribute['error']);
                            $transaction->rollBack();
                        } else {
                            $transaction->commit();
                        }
                        exit;
                    } else {
                        $transaction->commit();
                    }


//                $product_name = $this->addlanguage(1, $languages, $model->id, $model, 'product_name');
//                $short_name = $this->addlanguage(1, $languages, $model->id, $model, 'short_description');
//                $long_name = $this->addlanguage(1, $languages, $model->id, $model, 'long_description');


                    return $this->redirect(['index']);
                } else {
                    $transaction->rollBack();
                    print_r($model->errors);
                    exit;
                }
            } catch (Exception $ex) {
                $transaction->rollBack();
            }
        }
        return $this->render('create', [
                    'model' => $model,
                    'modelcat' => $modelcat,
                    'product_attribute' => $product_attribute,
                    'attribute' => $attribute,
                    'languages' => $languages,
        ]);
    }

    /**
     * Updates an existing ProductsServices model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $implode_search_tag = "";
     
        $modelcat = new \common\models\Category();
        $product_attribute = new \common\models\ProductAttributesValue();
        $attribute = new \common\models\Attributes();
        $languages = \common\models\Language::find()->where(['status' => 1])->all();
        $exist_image = $model->image;
        $exist_banner = $model->banner_image;
        $exist_gal_image = $model->gallery;
        $id = $model->id;
        if ($model->load(Yii::$app->request->post())) {

            $canon_name = strtolower($model->package_title  . ' ' . $model->category->category_name);
            $canonical_name = str_replace(' ', '-', $canon_name); // Replaces all spaces with hyphens.
            $canonical_name = preg_replace('/[^A-Za-z0-9\-]/', '', $canonical_name); // Removes special chars.
            $model->canonical_name = preg_replace('/-+/', '-', $canonical_name);
            if (isset($_POST['search_tag']) && $_POST['search_tag'] != NULL) {
                $implode_search_tag = implode(',', $_POST['search_tag']);
            }
            if (isset($_POST['related_products']) && $_POST['related_products'] != NULL) {
                $related_products = implode(',', $_POST['related_products']);
            }
            if (!isset($_POST['ProductsServices']['sort_order']) || $_POST['ProductsServices']['sort_order'] == NULL) {
                $model->sort_order = 0;
            }
            if ($implode_search_tag != '') {
                $model->search_tag = $implode_search_tag;
            }
            // if ($related_products != '') {
            //     $model->related_products = $related_products;
            // }
            $model->created_by = yii::$app->user->identity->id;
            $model->updated_by = yii::$app->user->identity->id;
            $model->status = 1;
         //   $model->title = $model->package_title ;
            $file = UploadedFile::getInstance($model, 'image');
            $gallery = UploadedFile::getInstances($model, 'gallery');
            $banner_image = UploadedFile::getInstance($model, 'banner_image');

            $name = md5(microtime());
            $profile_name = 'image' . $name;
            $banner_name = 'banner' . $name;
            if ($file) {
                $model->image = $profile_name . '.' . $file->extension;
            } else {
                $model->image = $exist_image;
            }
            if ($banner_image) {
                $model->banner_image = $banner_name . '.' . $banner_image->extension;
            } else {
                $model->banner_image = $exist_banner;
            }
            $model->gallery = $exist_gal_image;

            $transaction = Yii::$app->db->beginTransaction();
            try {


                if ($model->save()) {

                    //$model->id = Yii::$app->params['sku_prefix'] . 'M' . $model->merchant_id . 'PS' . $model->id;
                    $model->save(FALSE);

                    if ($id != $model->id) {
                        $oldfolder = 'products/' . base64_encode($id);
                        $newfolder = 'products/' . base64_encode($model->id);
                        $targetFolder = \yii::$app->basePath . '/../uploads/' . $oldfolder . '/';
                        $newtargetFolder = \yii::$app->basePath . '/../uploads/' . $newfolder . '/';
                        if (file_exists($targetFolder)) {
                            rename($targetFolder, $newtargetFolder);
                        }
                    }
                    if ($file) {
                        if (!$model->uploadFile($file, $profile_name, 'products/' . base64_encode($model->id) . '/image')) {

                            $transaction->rollBack();
                        }
                    }
                    if ($banner_image) {
                        $model->uploadBanner($banner_image, $banner_name, 'product-banner/' . $model->id . '/image');
                    }
                    if ($gallery != NULL) {
                        if (!$model->uploadMultipleImage($gallery, $model->id, $name, 'products/' . base64_encode($model->id) . '/gallery')) {

                            $transaction->rollBack();
                        }
                    }
                    if (Yii::$app->request->post('ProductAttributesValue') && Yii::$app->request->post('ProductAttributesValue') != NULL) {
                        $manageAttribute = $this->newmanageAttribute($model, Yii::$app->request->post('ProductAttributesValue'));

                        if ($manageAttribute['status'] == 411) {
                            $product_attribute->addError('error', $manageAttribute['error']);
                            $transaction->rollBack();
                            print_r($manageAttribute['error']);
                        } else {
                            $transaction->commit();
                            //echo "ok";
                        }
                    } else {
                        $transaction->commit();
                    }

                  //  Yii::$app->session->setFlash('success', "Packages updated successfully.");

                    return $this->redirect(['index']);
                } else {
                    $transaction->rollBack();
                    print_r($model->errors);
                    exit;
                }
            } catch (Exception $ex) {
                $transaction->rollBack();
            }
        }

        return $this->render('update', [
                    'model' => $model,
                    'modelcat' => $modelcat,
                    'product_attribute' => $product_attribute,
                    'attribute' => $attribute,
                    'languages' => $languages,
        ]);
    }

    /**
     * Deletes an existing ProductsServices model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
    
        $model = $this->findModel($id);
        $package_date_model   = new PackagesDate();
        $pkg_price_model      = new PackagesPrice();
        $status = $package_date_model::deleteAll(['package_id' => $id]);
        if($status == 1) {
            $price_delete = $pkg_price_model::deleteAll(['package_id' => $id]);
            if($price_delete == 1) { 
                $this->findModel($id)->delete();
            }
        }
        return $this->redirect(['index']);
    }

    public function actionGalleryDelete() {
        $image = $_GET['item'];
        $id = $_GET['id'];
        $model = $this->findModel($id);

        if (is_dir(Yii::$app->basePath . '/../uploads/products/' . base64_encode($model->id) . '/gallery')) {
            chmod(Yii::$app->basePath . '/../uploads/products/' . base64_encode($model->id) . '/gallery', 0777);

            $data = Yii::$app->basePath . '/../uploads/products/' . $model->id . '/gallery/' . $image;
            $small = Yii::$app->basePath . '/../uploads/products/' . $model->id . '/gallery/small/' . $image;
            $medium = Yii::$app->basePath . '/../uploads/products/' . $model->id . '/gallery/medium/' . $image;
            $large = Yii::$app->basePath . '/../uploads/products/' . $model->id . '/gallery/large/' . $image;
            if (file_exists($data)) {
                chmod($data, 0777);
                unlink($data);
                if (file_exists($small)) {
                    chmod($small, 0777);
                    unlink($small);
                }
                if (file_exists($medium)) {
                    chmod($medium, 0777);
                    unlink($medium);
                }
                if (file_exists($large)) {
                    chmod($large, 0777);
                    unlink($large);
                }
            }

            $gallery = explode(',', $model->gallery);
            $array1 = Array($image);
            $array3 = array_diff($gallery, $array1);
            $model->gallery = implode(',', $array3);
            $model->save(FALSE);


            Yii::$app->session->setFlash('success', " Gallery image deleted successfully.");
            $this->redirect(array('products-services/update?id=' . $id));
        }
    }

    /**
     * Finds the ProductsServices model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductsServices the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ProductsServices::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
