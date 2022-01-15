<?php

namespace backend\modules\products\controllers;

use Yii;
use common\models\ProductsServices;
use common\models\ProductsServicesSearch;
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

        $searchModel = new ProductsServicesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGetAttributes($q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new \yii\db\Query;

            $query->select('id, name AS text')
                    ->from('attributes')
                    ->where(['like', 'name', $q])
                    ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => City::find($id)->name];
        }
        return $out;
    }

    public function actionUpdateProductAttribute() {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            $model = \common\models\ProductAttributesValue::find()->where(['id' => $_POST['ProductAttributesValue']['id']])->one();
            if ($model != NULL) {
                if (\common\models\ProductAttributesValue::updateAll(['price_status' => 0], ['AND', 'product_id', $model->product_id])) {

                    if ($model->load(Yii::$app->request->post())) {
                        if ($model->save()) {
                            $array['status'] = 200;
                            $array['error'] = '';
                            $array['message'] = 'Update Successfully.';
                        } else {
                            $array['status'] = 201;
                            $array['error'] = $model->errors;
                            $array['message'] = 'Error.';
                        }
                    } else {
                        $array['status'] = 201;
                        $array['error'] = $model->errors;
                        $array['message'] = 'Data Not Requested.';
                    }
                } else {
                    $array['status'] = 201;
                    $array['error'] = [];
                    $array['message'] = 'Update Failed.';
                }
            } else {
                $array['status'] = 201;
                $array['error'] = '';
                $array['message'] = 'No such information found.';
            }

            echo json_encode($array);
            exit;
        }
    }

    public function actionDeleteProductAttribute() {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            $model = \common\models\ProductAttributesValue::find()->where(['id' => $_POST['product_attr_id']])->one();
            if ($model != NULL) {
                if ($model->delete()) {
                    $array['status'] = 200;
                    $array['error'] = '';
                    $array['message'] = 'Delete Successfully.';
                } else {
                    $array['status'] = 201;
                    $array['error'] = $model->errors;
                    $array['message'] = 'Error.';
                }
            } else {
                $array['status'] = 201;
                $array['error'] = '';
                $array['message'] = 'No such information found.';
            }

            echo json_encode($array);
            exit;
        }
    }

    public function actionDeleteProductAttributeMain() {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            $model = \common\models\ProductAttributes::find()->where(['id' => $_POST['product_attr_id']])->one();
            if ($model != NULL) {
                if (\common\models\ProductAttributesValue::deleteAll(['AND', 'product_attributes_id = :product_attr_id'], [':product_attr_id' => $_POST['product_attr_id']])) {

                    if ($model->delete()) {
                        $array['status'] = 200;
                        $array['error'] = '';
                        $array['message'] = 'Delete Successfully.';
                    } else {
                        $array['status'] = 201;
                        $array['error'] = $model->errors;
                        $array['message'] = 'Error.';
                    }
                } else {
                    $array['status'] = 201;
                    $array['error'] = '';
                    $array['message'] = 'No such information found...';
                }
            } else {
                $array['status'] = 201;
                $array['error'] = '';
                $array['message'] = 'No such information found.';
            }

            echo json_encode($array);
            exit;
        }
    }

    public function actionGetAttrValues() {
        $request = Yii::$app->request;
        if ($request->isAjax) {

            $attr_name = $_POST['attribute'];
            $get_data = \common\models\Attributes::find()->where(['status' => 1, 'name' => $attr_name])->one();
            $data = '<option value="">Choose a Attribute</option>';

            if ($get_data != NULL) {
                $get_attr_values = \common\models\AttributesValue::find()->where(['status' => 1, 'attributes_id' => $get_data->id])->all();
                if ($get_attr_values != NULL) {
                    $data = $this->renderPartial('_attr_values', [
                        'models' => $get_attr_values,
                    ]);
                    $array['status'] = 200;
                    $array['error'] = '';
                    $array['message'] = $data;
                } else {
                    $array['status'] = 204;
                    $array['error'] = '';
                    $array['message'] = 'No Attribute Value Exist.';
                }
            } else {
                $attrmodal = new \common\models\Attributes();
                $attrmodal->created_by = yii::$app->user->identity->id;
                $attrmodal->updated_by = yii::$app->user->identity->id;
                $attrmodal->created_by_type = 2;  //1-User , 2- Admin, 3-Merchant, 4-Franchise
                $attrmodal->updated_by_type = 2;  //1-User , 2- Admin, 3-Merchant, 4-Franchise
                $attrmodal->sort_order = 0;
                $attrmodal->status = 0;
                $attrmodal->name = $attr_name;
                $attrmodal->save(false);
                $array['status'] = 201;
                $array['error'] = '';
                $array['message'] = 'No Attribute Value Exist.';
            }


            echo json_encode($array);
            exit;
        }
    }

    public function actionGetDiscounts() {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $merchant = \common\models\Merchant::findOne(['id' => $_POST['merchant_id']]);
            $category = [];
            if ($merchant != NULL) {
                if ($merchant->category != NULL) {
                    $exp_category = explode(',', $merchant->category);
                    if ($exp_category != NULL) {
                        $get_categorys = \common\models\Category::find()->where(['IN', 'id', $exp_category])->all();
                        if ($get_categorys != NULL) {
                            foreach ($get_categorys as $get_category) {
                                array_push($category, ['id' => $get_category->id, 'text' => $get_category->category_name]);
                            }
                        }
                    }
                }
            }
            $merchant_id = $_POST['merchant_id'];
            $get_discounts = \common\models\Discounts::find()->where(['status' => 1]);

            if (isset($_POST['merchant_id']) && $merchant_id != "") {
                $get_discounts->andWhere(['merchant_id' => $merchant_id]);
                $get_discounts->orWhere("merchant_id IS NULL");
            } else {
                $get_discounts->andWhere("merchant_id IS NULL");
            }

            $get_data = $get_discounts->all();
            $data = '<option value="">Choose a Discount</option>';

            if ($get_data != NULL) {
                $data = $this->renderPartial('_discounts', [
                    'models' => $get_data,
                ]);
                $array['status'] = 200;
                $array['error'] = '';
                $array['message']['discount'] = $data;
                $array['message']['category'] = $category;
            } else {
                $array['status'] = 201;
                $array['error'] = 'No Data Found';
                $array['message']['discount'] = $data;
                $array['message']['category'] = $category;
            }


            echo json_encode($array);
            exit;
        }
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

    public function actionAddAttribute() {
        if (isset($_POST['attr_name'])) {
            $attr_name = $_POST['attr_name'];
        }
        if (isset($_POST['attr_value'])) {
            $attr_value = $_POST['attr_value'];
        }
        $attribute_exist = \common\models\Attributes::find()->where(['id' => $attr_name])->one();
        $model = new \common\models\Attributes();
        $transaction = Yii::$app->db->beginTransaction();

        if ($attribute_exist == NULL) {
            $model->created_by = yii::$app->user->identity->id;
            $model->updated_by = yii::$app->user->identity->id;
            $model->created_by_type = 2;  //1-User , 2- Admin, 3-Merchant, 4-Franchise
            $model->updated_by_type = 2;  //1-User , 2- Admin, 3-Merchant, 4-Franchise
            $model->sort_order = 0;
            $model->name = $attr_name;
            $model->status = 1;
            if ($model->save()) {
                foreach ($attr_value as $attr_val) {
                    $check_model = \common\models\AttributesValue::find()->where(['value' => $attr_val, 'attributes_id' => $model->id])->one();
                    if ($check_model == NULL) {
                        $newmodel = new \common\models\AttributesValue();
                        $newmodel->attributes_id = $model->id;
                        $newmodel->value = $attr_val;
                        $newmodel->status = 1;
                        $newmodel->created_by = yii::$app->user->identity->id;
                        $newmodel->updated_by = yii::$app->user->identity->id;
                        $newmodel->created_by_type = 2;  //1-User , 2- Admin, 3-Merchant, 4-Franchise
                        $newmodel->updated_by_type = 2;  //1-User , 2- Admin, 3-Merchant, 4-Franchise
                        $newmodel->sort_order = 0;
                        if ($newmodel->save(FALSE)) {

                        }
                    }
                }
                $transaction->commit();
                $array['status'] = 200;
                $array['error'] = '';
                $array['message'] = 'Attribute Add Successfully.';
            } else {
                $transaction->rollBack();
                $array['status'] = 201;
                $array['error'] = $model->errors;
                $array['message'] = 'Form Error Found';
            }
        } else {
            if ($attr_value != NULL) {
                foreach ($attr_value as $attr_val) {
                    $check_model = \common\models\AttributesValue::find()->where(['value' => $attr_val, 'attributes_id' => $attribute_exist->id])->one();
                    if ($check_model == NULL) {
                        $newmodel = new \common\models\AttributesValue();
                        $newmodel->attributes_id = $attribute_exist->id;
                        $newmodel->value = $attr_val;
                        $newmodel->status = 1;
                        $newmodel->created_by = yii::$app->user->identity->id;
                        $newmodel->updated_by = yii::$app->user->identity->id;
                        $newmodel->created_by_type = 2;  //1-User , 2- Admin, 3-Merchant, 4-Franchise
                        $newmodel->updated_by_type = 2;  //1-User , 2- Admin, 3-Merchant, 4-Franchise
                        $newmodel->sort_order = 0;
                        if ($newmodel->save(FALSE)) {

                        }
                    }
                }
                $transaction->commit();
                $array['status'] = 200;
                $array['error'] = '';
                $array['message'] = 'Attribute Add Successfully.';
            } else {
                $transaction->rollBack();
            }
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
        if (\Yii::$app->user->identity->interface == 'franchise') {
            $get_merchant = \common\models\Merchant::find()->select('id')->where(['franchise_id' => \Yii::$app->user->identity->id])->asArray()->all();
            $merchant_array = array_column($get_merchant, 'id');
            if (!in_array($model->merchant_id, $merchant_array)) {
                throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        }
        if (Yii::$app->user->identity->interface == 'merchant') {
            if ($model->merchant_id != Yii::$app->user->identity->id) {
                throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        }
        return $this->render('view', [
                    'model' => $this->findModel($id),
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

        if ($model->load(Yii::$app->request->post())) {
            $canon_name = strtolower($model->product_name_en . ' ' . $model->category->category_name . ' ' . $model->merchant->id);
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
            if ($related_products != '') {
                $model->related_products = $related_products;
            }
            $model->created_by = yii::$app->user->identity->id;
            $model->updated_by = yii::$app->user->identity->id;
            $model->status = 1;
            $model->title = $model->product_name_en;
            $file = UploadedFile::getInstance($model, 'image');
            $gallery = UploadedFile::getInstances($model, 'gallery');
            $name = md5(microtime());
            $profile_name = 'image' . $name;
            if ($file) {
                $model->image = $profile_name . '.' . $file->extension;
            }

            $model->gallery = "";
            $transaction = Yii::$app->db->beginTransaction();
            try {


                if ($model->save()) {

                    $model->sku = Yii::$app->params['sku_prefix'] . 'M' . $model->merchant_id . 'PS' . $model->id;
                    $model->save(FALSE);
                    if ($file) {
                        if (!$model->uploadFile($file, $profile_name, 'products/' . base64_encode($model->sku) . '/image')) {
                            $transaction->rollBack();
                        }
                    }
                    if ($gallery != NULL) {
                        if (!$model->uploadMultipleImage($gallery, $model->id, $name, 'products/' . base64_encode($model->sku) . '/gallery')) {
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
                    } else {
                        $transaction->commit();
                    }


//                $product_name = $this->addlanguage(1, $languages, $model->id, $model, 'product_name');
//                $short_name = $this->addlanguage(1, $languages, $model->id, $model, 'short_description');
//                $long_name = $this->addlanguage(1, $languages, $model->id, $model, 'long_description');


                    return $this->redirect(['view', 'id' => $model->id]);
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

    private function getAttributes($attributes_name_en, $attributes_name_ar) {
        $check_Attribute_exist = \common\models\Attributes::find()->where(['name' => $attributes_name_en, 'name_ar' => $attributes_name_ar])->one();

        if ($check_Attribute_exist != NULL) {

            return $check_Attribute_exist->id;
        } else {

            $newAttributes = new \common\models\Attributes();
            $newAttributes->name = $attributes_name_en;
            $newAttributes->name_ar = $attributes_name_ar;
            $newAttributes->status = 0;
            $newAttributes->created_by = yii::$app->user->identity->id;
            $newAttributes->created_by_type = 2;
            $newAttributes->updated_by = yii::$app->user->identity->id;
            $newAttributes->updated_by_type = 2;
            $newAttributes->sort_order = 0;
            if ($newAttributes->save()) {

                return $newAttributes->id;
            } else {
                //echo $attributes_name_en . "--" . $attributes_name_ar;

                print_r($newAttributes->errors);
                exit;
                return 0;
            }
        }
    }

    private function getAttributesValue($attribute_id, $attr_val_en, $attr_val_ar) {
        $check_Attribute_exist = \common\models\AttributesValue::find()->where(['attributes_id' => $attribute_id, 'value' => $attr_val_en, 'value_ar' => $attr_val_ar])->one();

        if ($check_Attribute_exist != NULL) {
            return $check_Attribute_exist->id;
        } else {
            $newAttributes = new \common\models\AttributesValue();
            $newAttributes->attributes_id = $attribute_id;
            $newAttributes->value = $attr_val_en;
            $newAttributes->value_ar = $attr_val_ar;
            $newAttributes->status = 0;
            $newAttributes->created_by = yii::$app->user->identity->id;
            $newAttributes->created_by_type = 2;
            $newAttributes->updated_by = yii::$app->user->identity->id;
            $newAttributes->updated_by_type = 2;
            $newAttributes->sort_order = 0;
            if ($newAttributes->save()) {
                return $newAttributes->id;
            } else {
                return 0;
            }
        }
    }

    private function productAttributes($model, $attribute_id, $price_status, $id) {
//        if ($id != 0 && $id != "") {
//            $check_Product_Attribute_exist = \common\models\ProductAttributes::find()->where(['product_id' => $model->id, 'id' => $id])->one();
//        } else {
//            $check_Product_Attribute_exist = \common\models\ProductAttributes::find()->where(['product_id' => $model->id, 'attributes_id' => $attribute_id])->one();
//        }
        $check_Product_Attribute_exist = \common\models\ProductAttributes::find()->where(['product_id' => $model->id, 'attributes_id' => $attribute_id])->one();
        if ($check_Product_Attribute_exist != NULL) {
            $newAttributes = $check_Product_Attribute_exist;
            if ($price_status == 1) {
                \common\models\ProductAttributes::updateAll(['price_status' => 0], ['and', 'product_id', $model->id]);
                $newAttributes->price_status = $price_status;
            } else {
                $newAttributes->price_status = $price_status;
            }
            if ($newAttributes->save()) {
                return $newAttributes->id;
            } else {
                return 0;
            }
        } else {
            $newAttributes = new \common\models\ProductAttributes();
            $newAttributes->product_id = $model->id;
            $newAttributes->attributes_id = $attribute_id;
            $newAttributes->status = 0;
            $newAttributes->created_by = yii::$app->user->identity->id;
            $newAttributes->created_by_type = 2;
            $newAttributes->updated_by = yii::$app->user->identity->id;
            $newAttributes->updated_by_type = 2;
            if ($price_status == 1) {
                \common\models\ProductAttributes::updateAll(['price_status' => 0], ['and', 'product_id', $model->id]);
                $newAttributes->price_status = $price_status;
            } else {
                $newAttributes->price_status = $price_status;
            }
            if ($newAttributes->save()) {
                return $newAttributes->id;
            } else {
                return 0;
            }
        }
    }

    public function newmanageAttribute($model, $attributes) {
//        echo "<pre/>";
//        print_r($attributes);
//        exit;
        $attr_id = $attributes['attribute_id'];
        $get_count = count($attr_id);
        $result = [];
        $error = [];
        if ($model != NULL) {
            if ($attributes != NULL) {
                if ($get_count > 0) {
                    for ($i = 0; $i < $get_count; $i++) {
                        if ($attributes['attribute_id'][$i] && $attributes['attribute_id'][$i] != NULL && $attributes['attribute_id'][$i] != "") {

                            $attributes_name_en = $attributes['attribute_id'][$i]['en'];
                            $attributes_name_ar = $attributes['attribute_id'][$i]['ar'];
                            $attribute_id = $this->getAttributes($attributes_name_en, $attributes_name_ar);
                            if ($attribute_id != 0) {
                                $product_attribute = $this->productAttributes($model, $attribute_id, isset($attributes['price_status'][$i]) ? $attributes['price_status'][$i] : 0, isset($attributes['product_attributes_id'][$i]) ? $attributes['product_attributes_id'][$i] : 0);
//                                echo isset($attributes['price_status'][$i]) ? $attributes['price_status'][$i] : 0;
                                if ($product_attribute != 0) {
                                    if ($attributes['attribute_value'][$i] && $attributes['attribute_value'][$i] != NULL && $attributes['attribute_value'][$i] != "") {
                                        $attr_values = $attributes['attribute_value'][$i];
                                        $cnt = count($attr_values['en']);
                                        for ($k = 0; $k < $cnt; $k++) {
                                            $attr_val_en = $attr_values['en'][$k];
                                            $attr_val_ar = $attr_values['ar'][$k];
                                            $productAttrValue = $this->getAttributesValue($attribute_id, $attr_val_en, $attr_val_ar);

                                            if (isset($attributes['sort_order'][$i][$k]) && $attributes['sort_order'][$i][$k] != NULL) {
                                                $sort_order = $attributes['sort_order'][$i][$k];
                                            } else {
                                                $sort_order = 0;
                                            }
                                            if (isset($attributes['price'][$i][$k]) && $attributes['price'][$i][$k] != NULL) {
                                                $price = $attributes['price'][$i][$k];
                                            } else {
                                                $price = 0;
                                            }
                                            if (isset($attributes['quantity'][$i][$k]) && $attributes['quantity'][$i][$k] != NULL) {
                                                $quantity = $attributes['quantity'][$i][$k];
                                            } else {
                                                $quantity = 0;
                                            }
                                            if (isset($attributes['id'][$i][$k]) && $attributes['id'][$i][$k] != NULL) {
                                                $product_attr_value_id = $attributes['id'][$i][$k];
                                            } else {
                                                $product_attr_value_id = 0;
                                            }
                                            $productAttrModel = new \common\models\ProductAttributesValue();
                                            if ($product_attr_value_id != 0 && $product_attr_value_id != "") {
                                                $check_product_model = \common\models\ProductAttributesValue::find()->where(['id' => $product_attr_value_id, 'product_id' => $model->id])->one();
                                            } else {
                                                $check_product_model = \common\models\ProductAttributesValue::find()->where(['attributes_value_id' => $productAttrValue, 'product_id' => $model->id])->one();
                                            }
                                            if ($check_product_model != NULL) {
                                                $productAttrModel = $check_product_model;
                                            }
                                            if ($productAttrValue != 0) {
                                                $productAttrModel->attributes_value_id = $productAttrValue;
                                            }
                                            $productAttrModel->product_attributes_id = $product_attribute;
                                            $productAttrModel->product_id = $model->id;
                                            $productAttrModel->status = 1;
                                            $productAttrModel->sort_order = 0;
                                            $productAttrModel->quantity = isset($quantity) ? $quantity : 0;
                                            $productAttrModel->price = isset($price) ? $price : 0;
                                            $productAttrModel->price_status = 0;
                                            $productAttrModel->created_by_type = 2;  //1-User , 2- Admin, 3-Merchant, 4-Franchise
                                            $productAttrModel->updated_by_type = 2;  //1-User , 2- Admin, 3-Merchant, 4-Franchise
                                            $productAttrModel->created_by = yii::$app->user->identity->id;
                                            $productAttrModel->updated_by = yii::$app->user->identity->id;
                                            if (!$productAttrModel->save()) {
                                                $error[] = $productAttrModel->errors;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else {

                $error[] = "Attribute Need to restart";
            }
        } else {

            $error[] = "Attribute Need to restart";
        }

        if ($error != NULL) {
            $result['status'] = 411;
            $result['error'] = $error;
        } else {
            $result['status'] = 200;
            $result['error'] = $error;
        }

        return $result;
    }

    protected function addlanguage($type, $languages, $item_id, $data, $field) {
        if ($languages != NULL) {
            foreach ($languages as $language) {
                $check_exist = \common\models\LanguageData::find()->where(['type' => $type, 'lang_id' => $language->id, 'item_id' => $item_id, 'field' => $field])->one();
                if ($check_exist != NULL) {
                    $check_exist->value = $data[$field][$language->shortcode];
                    $check_exist->save(FALSE);
                } else {
                    $model = new \common\models\LanguageData();
                    $model->type = $type;
                    $model->lang_id = $languages->id;
                    $model->item_id = $item_id;
                    $model->value = $data[$field][$language->shortcode];
                    $model->status = 1;
                    $model->field = $field;
                    $model->created_by = yii::$app->user->identity->id;
                    $model->updated_by = yii::$app->user->identity->id;
                    if ($model->save(FALSE)) {
                        return $model->id;
                    }
                }
            }
        }
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
        if (Yii::$app->user->identity->interface == 'merchant') {
            if ($model->merchant_id != Yii::$app->user->identity->id) {
                throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        }
        if (\Yii::$app->user->identity->interface == 'franchise') {
            $get_merchant = \common\models\Merchant::find()->select('id')->where(['franchise_id' => \Yii::$app->user->identity->id])->asArray()->all();
            $merchant_array = array_column($get_merchant, 'id');
            if (!in_array($model->merchant_id, $merchant_array)) {
                throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        }
        $modelcat = new \common\models\Category();
        $product_attribute = new \common\models\ProductAttributesValue();
        $attribute = new \common\models\Attributes();
        $languages = \common\models\Language::find()->where(['status' => 1])->all();
        $exist_image = $model->image;
        $exist_gal_image = $model->gallery;
        $sku = $model->sku;
        if ($model->load(Yii::$app->request->post())) {

            $canon_name = strtolower($model->product_name_en . ' ' . $model->category->category_name . ' ' . $model->merchant->id);
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
            if ($related_products != '') {
                $model->related_products = $related_products;
            }
            $model->created_by = yii::$app->user->identity->id;
            $model->updated_by = yii::$app->user->identity->id;
            $model->status = 1;
            $model->title = $model->product_name_en;
            $file = UploadedFile::getInstance($model, 'image');
            $gallery = UploadedFile::getInstances($model, 'gallery');
            $name = md5(microtime());
            $profile_name = 'image' . $name;
            if ($file) {
                $model->image = $profile_name . '.' . $file->extension;
            } else {
                $model->image = $exist_image;
            }
            $model->gallery = $exist_gal_image;

            $transaction = Yii::$app->db->beginTransaction();
            try {


                if ($model->save()) {

                    $model->sku = Yii::$app->params['sku_prefix'] . 'M' . $model->merchant_id . 'PS' . $model->id;
                    $model->save(FALSE);

                    if ($sku != $model->sku) {
                        $oldfolder = 'products/' . base64_encode($sku);
                        $newfolder = 'products/' . base64_encode($model->sku);
                        $targetFolder = \yii::$app->basePath . '/../uploads/' . $oldfolder . '/';
                        $newtargetFolder = \yii::$app->basePath . '/../uploads/' . $newfolder . '/';
                        if (file_exists($targetFolder)) {
                            rename($targetFolder, $newtargetFolder);
                        }
                    }
                    if ($file) {
                        if (!$model->uploadFile($file, $profile_name, 'products/' . base64_encode($model->sku) . '/image')) {

                            $transaction->rollBack();
                        }
                    }
                    if ($gallery != NULL) {
                        if (!$model->uploadMultipleImage($gallery, $model->id, $name, 'products/' . base64_encode($model->sku) . '/gallery')) {

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

                    Yii::$app->session->setFlash('success', "Products updated successfully.");

                    return $this->redirect(['view', 'id' => $model->id]);
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
        if (\Yii::$app->user->identity->interface == 'franchise') {
            $get_merchant = \common\models\Merchant::find()->select('id')->where(['franchise_id' => \Yii::$app->user->identity->id])->asArray()->all();
            $merchant_array = array_column($get_merchant, 'id');
            if (!in_array($merchant_array->merchant_id, $merchant_array)) {
                throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        }
        $model = $this->findModel($id);
        if (Yii::$app->user->identity->interface == 'merchant') {
            if ($model->merchant_id != Yii::$app->user->identity->id) {
                throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionGalleryDelete() {
        $image = $_GET['item'];
        $id = $_GET['id'];
        $model = $this->findModel($id);

        if (is_dir(Yii::$app->basePath . '/../uploads/products/' . base64_encode($model->sku) . '/gallery')) {
            chmod(Yii::$app->basePath . '/../uploads/products/' . base64_encode($model->sku) . '/gallery', 0777);

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
