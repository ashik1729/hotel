<?php

namespace backend\controllers;

use Yii;
use common\models\AdminRole;
use common\models\AdminRoleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdminRoleController implements the CRUD actions for AdminRole model.
 */
class AjaxController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
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
     * Lists all AdminRole models.
     * @return mixed
     */
    public function actionGetStates() {
        $request = Yii::$app->request;
        if ($request->isAjax) {

            $country_id = $_POST['country_id'];
            $get_data = \common\models\States::find()->where(['status' => 1, 'country_id' => $country_id])->all();
            $data = '<option value="">Select State</option>';
            if ($get_data != NULL) {
                $data = $this->renderPartial('_states', [
                    'models' => $get_data,
                ]);
            }
            echo $data;
        }
    }

    public function actionGetCity() {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $country_id = $_POST['country_id'];
            $get_data_query = \common\models\City::find()->where(['status' => 1, 'country' => $country_id]);
            if (isset($_POST['state_id']) && $_POST['state_id'] != "") {
                $state_id = $_POST['state_id'];
                $get_data_query->andWhere(['state' => $state_id]);
            }
            $get_data = $get_data_query->all();
            $data = '<option value="">Select City</option>';
            if ($get_data != NULL) {
                $data = $this->renderPartial('_city', [
                    'models' => $get_data,
                ]);
            }
            echo $data;
        }
    }

    public function actionGetProductAttributes() {
        $request = Yii::$app->request;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $product_type = 0;
        if ($request->isAjax) {
            $attributes = [];
            $product_id = $_POST['product_id'];
            $product = \common\models\ProductsServices::find()->where(['id' => $_POST['product_id']])->one();
            $product_type = $product->type;

            $get_attributes = \common\models\ProductAttributesValue::find()
                            ->select("product_attributes_value.id,attributes_value_id,price,attributes_value.value as attributes_value,attributes.name as name,attributes.id as attributes_id")
                            ->where(['product_attributes_value.status' => 1, 'product_attributes_value.product_id' => $product_id])
                            ->innerJoinWith('attributesValue', false)
                            ->join('LEFT OUTER JOIN', 'attributes', 'attributes_value.attributes_id =attributes.id')
                            ->orderBy(['product_attributes_value.sort_order' => SORT_ASC])
                            ->asArray()->all();
            $attributes_lists = array_column($get_attributes, 'attributes_id');
            $attributes_lists = array_unique($attributes_lists);
            if ($attributes_lists != NULL) {
                foreach ($attributes_lists as $attributes_list) {
                    $product_attr_items = [];
                    foreach ($get_attributes as $get_attribute) {
                        if ($attributes_list == $get_attribute['attributes_id']) {
                            array_push($product_attr_items, $get_attribute);
                            $name = $get_attribute['name'];
                        }
                    }
                    array_push($attributes, ['attribute_id' => $attributes_list, 'attribute_name' => $name, 'attr_items' => $product_attr_items]);
                }
            }

            $data = '';
            if ($attributes != NULL) {
                $data = $this->renderPartial('_get_attr_value', [
                    'models' => $attributes,
                ]);
            }
        }

        $array['status'] = 200;
        $array['error'] = '';
        $array['message']['attributes'] = $data;
        $array['message']['product_type'] = $product_type;
        echo json_encode($array);
        exit;
    }

    public function actionGetProductAvailableSlots() {
        $request = Yii::$app->request;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $product_type = 0;
        if ($request->isAjax) {
            $attributes = [];
            $product_id = $_POST['product_id'];
            $product = \common\models\ProductsServices::find()->where(['id' => $_POST['product_id']])->one();
            $product_type = $product->type;


            $data_slots = '';
            if ($product->type == 2 || $product->type == 3) {

                $day = date('l', strtotime($_POST['date']));
                $get_week_day = \common\models\WeekDaysAvailability::find()->where(['merchant_id' => $product->merchant_id, 'day' => $day, 'availability' => 1])->one();
                if ($get_week_day != NULL) {

                    $get_disable_slots = \common\models\DisableSlots::find()->where("slot_from >= '" . $get_week_day->available_from . "' AND slot_to <= '" . $get_week_day->available_to . "'")->andWhere(['merchant_id' => $product->merchant_id, 'day' => $get_week_day->id])->all();
                    $data_slots = $this->renderPartial('_get_slots', [
                        'disable_slots' => $get_disable_slots,
                        'available_from' => $get_week_day->available_from,
                        'available_to' => $get_week_day->available_to,
                        'interval' => $get_week_day->slot_interval,
                        'get_slotmodel' => $get_week_day,
                    ]);
                }
            }
        }

        $array['status'] = 200;
        $array['error'] = '';
        $array['message']['booking_slots'] = $data_slots;
        $array['message']['product_type'] = $product_type;
        echo json_encode($array);
        exit;
    }

    public function actionGetArea() {
        $request = Yii::$app->request;
        if ($request->isAjax) {

            $city_id = $_POST['city_id'];
            $get_data = \common\models\Area::find()->where(['status' => 1, 'city' => $city_id])->all();
            $data = '<option value="">Select Area</option>';
            if ($get_data != NULL) {
                $data = $this->renderPartial('_area', [
                    'models' => $get_data,
                ]);
            }
            echo $data;
        }
    }

    public
            function actionGetItemList($store_id, $q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new \yii\db\Query;
            $query->select('id,sku,price,short_description_en, product_name_en AS text, image')
                    ->from('products_services')
                    ->where(['like', 'product_name_en', $q])
                    ->orWhere(['like', 'sku', $q])
                    ->limit(20);

            $get_merchant = \common\models\Merchant::find()->select('id')->where(['franchise_id' => $store_id])->asArray()->all();
            $merchant_array = array_column($get_merchant, 'id');
            $query->andFilterWhere([
                'merchant_id' => $merchant_array,
            ]);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $data = array_values($data);
            $newdata = [];
            foreach ($data as $dat) {
                $dat['price'] = Yii::$app->Currency->convert($dat['price'], $store_id);
                $imgBasePath = \Yii::$app->basePath . '/../uploads/products/' . base64_encode($dat['sku']) . '/image/' . $dat['image'];
                if (!file_exists($imgBasePath)) {
                    $imgPath = \Yii::$app->params['website'] . 'admin/img/no-image.jpg';
                    $dat['image'] = $imgPath;
                } else {
                    $dat['image'] = \Yii::$app->params['website'] . '/../uploads/products/' . base64_encode($dat['sku']) . '/image/' . $dat['image'];
                }

                $newdata[] = $dat;
            }
            $out['items'] = $newdata;
        } elseif ($id > 0) {
            $get_products = \common\models\ProductsServices::find($id);
            $out['items'] = ['id' => $id, 'sku' => $get_products->sku, 'short_description_en' => $get_products->short_description_en, 'text' => $get_products->product_name_en, 'image' => $get_products->image];
        }
        return $out;
    }

    public function actionGetAttributesList($q = null, $id = null, $lang = 1) {
        $request = Yii::$app->request;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if ($request->isAjax) {
            $out = [];
            if (!is_null($q)) {
                $query = \common\models\Attributes::find()->andWhere(['status' => 1]);
                if ($lang == 1) {
                    $query->andWhere(['like', 'name', $q]);
                }
                if ($lang == 2) {
                    $query->andWhere(['like', 'name_ar', $q]);
                }
                $data = $query->all();
                $newdata = [];
                foreach ($data as $dat) {
                    $newdata[] = ['id' => $lang == 1 ? $dat->name : $dat->name_ar, 'text' => $lang == 1 ? $dat->name : $dat->name_ar, 'text_en' => $dat->name, 'text_ar' => $dat->name_ar];
                    //   array_push($out, ['id' => $dat->id, 'text' => $lang == 1 ? $dat->name : $dat->name_ar]);
                }
                $out = $newdata;
            } elseif ($id != "") {

                $dat = [];
                if ($lang == 1) {
                    $dat = \common\models\Attributes::findOne(['name' => $id]);
                } else if ($lang == 2) {
                    $dat = \common\models\Attributes::findOne(['name_ar' => $id]);
                }
                if ($dat != NULL) {

                    $out = ['id' => $lang == 1 ? $dat->name : $dat->name_ar, 'text' => $lang == 1 ? $dat->name : $dat->name_ar, 'text_en' => $dat->name, 'text_ar' => $dat->name_ar];
                }
            }
            return $out;
        }
    }

    public function actionGetAttributesValueList($q = null, $lang = 1) {
        $request = Yii::$app->request;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if ($request->isAjax) {
            $out = [];
            if (!is_null($q)) {
                $query = \common\models\AttributesValue::find()->andWhere(['status' => 1]);
                if ($lang == 1) {
                    $query->andWhere(['like', 'value', $q]);
                }
                if ($lang == 2) {
                    $query->andWhere(['like', 'value_ar', $q]);
                }
                $data = $query->all();
                foreach ($data as $dat) {
                    array_push($out, ['id' => $lang == 1 ? $dat->value : $dat->value_ar, 'text' => $lang == 1 ? $dat->value : $dat->value_ar, 'text_en' => $dat->value, 'text_ar' => $dat->value_ar]);
                }
            }
            return $out;
        }
    }

}
