<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class Products extends Component {

    public function Price($model, $product_attr_id = 0) {
        $current_date = date('Y-m-d');
        $base_price = $model->price;
        if ($product_attr_id != 0) {
            $get_product_attribute = \common\models\ProductAttributesValue::find()->where(['product_id' => $model->id, 'id' => $product_attr_id])->one();
            if ($get_product_attribute != NULL) {
                if ($get_product_attribute->price != "0.00" && $get_product_attribute->price != 0) {
                    $base_price = $get_product_attribute->price;
                }
            }
        }
        $display_price = $base_price;
        if ($model->discount_rate != NULL && $model->discount_rate > 0 && $model->discount_from <= $current_date && $model->discount_to >= $current_date) {
//        if ($model->discount_from <= $current_date && $model->discount_to >= $current_date) {
            if ($model->discount_rate != 0) {
                if ($model->discount_type == 1) {
                    $display_price = $base_price - $model->discount_rate;
                } else if ($model->discount_type == 2) {
                    $display_price = $base_price - ($base_price * $model->discount_rate / 100);
                }
            }
        }
        return floatval($display_price);
    }

    public function PriceConvert($model, $lang = 1) {
//        $current_date = date('Y-m-d');
//        $base_price = $model->price;
//        if ($product_attr_id != 0) {
//            $get_product_attribute = \common\models\ProductAttributesValue::find()->where(['product_id' => $model->id, 'id' => $product_attr_id])->one();
//            if ($get_product_attribute != NULL) {
//                if ($get_product_attribute->price != "0.00" && $get_product_attribute->price != 0) {
//                    $base_price = $get_product_attribute->price;
//                }
//            }
//        }
//        $display_price = $base_price;
//        if ($model->discounts != NULL && $model->discounts->discount_from <= $current_date && $model->discounts->discount_to >= $current_date) {
////        if ($model->discount_from <= $current_date && $model->discount_to >= $current_date) {
//            if ($model->discounts->discount_rate != 0) {
//                if ($model->discounts->discount_type == 1) {
//                    $display_price = $base_price - $model->discount_rate;
//                } else if ($model->discounts->discount_type == 2) {
//                    $display_price = $base_price - ($base_price * $model->discounts->discount_rate / 100);
//                }
//            }
//        }

        $convert_price = Yii::$app->Currency->Convert(floatval(Yii::$app->Products->price($model)), $model->merchant->franchise_id, $lang);

        return $convert_price;
    }

    public function DiscountPrice($model, $product_attr_id = 0) {
        $current_date = date('Y-m-d');
        $base_price = $model->price;

        if ($product_attr_id != 0) {
            $get_product_attribute = \common\models\ProductAttributesValue::find()->where(['product_id' => $model->id, 'id' => $product_attr_id])->one();
            if ($get_product_attribute != NULL) {
                if ($get_product_attribute->price != "0.00" && $get_product_attribute->price != 0) {
                    $base_price = $get_product_attribute->price;
                }
            }
        }
        $display_price = $base_price;
        if ($model->discount_rate != NULL && $model->discount_rate > 0 && $model->discount_from <= $current_date && $model->discount_to >= $current_date) {
//        if ($model->discount_from <= $current_date && $model->discount_to >= $current_date) {
            if ($model->discount_rate != 0) {
                if ($model->discount_type == 1) {
                    $display_price = $base_price - $model->discount_rate;
                } else if ($model->discount_type == 2) {
                    $display_price = $base_price - ($base_price * $model->discount_rate / 100);
                }
            }
        }
        $discount_price = $display_price;
        if ($model->discount_id != NULL && $model->discount_id != 0) {

            if ($model->discounts->discount_from <= $current_date && $model->discounts->discount_to >= $current_date) {

                if ($model->discounts->discount_type == 1) {
                    $discount_price = $display_price - $model->discounts->discount_rate;
                } else if ($model->discounts->discount_type == 2) {
                    $discount_price = $display_price - ($display_price * $model->discounts->discount_rate / 100);
                }
            }
        }

        return $discount_price;
    }

}
