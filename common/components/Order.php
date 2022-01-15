<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class Order extends Component {

    public function Subtotal($order_id, $merchant_id = 0) {
        $subtotal = 0;
        if (isset($order_id) && $order_id != 0 && $order_id != "") {
            $orderProductsQuery = \common\models\OrderProducts::find()->where(['order_id' => $order_id]);
            if ($merchant_id != 0) {
                $orderProductsQuery->andWhere(['merchant_id' => $merchant_id]);
            }
            $orderProducts = $orderProductsQuery->all();
            if ($orderProducts != NULL) {
                foreach ($orderProducts as $orderProduct) {
                    $subtotal += $orderProduct->amount * $orderProduct->quantity;
                }
            }
        }

        return $subtotal;
    }

    public function Grandtotal($order_id, $merchant_id = 0) {
        $grandTotal = 0;
        if (isset($order_id) && $order_id != 0 && $order_id != "") {
            $grandTotal = $this->Subtotal($order_id, $merchant_id) + $this->Shipping($order_id, $merchant_id);
        }

        return $grandTotal;
    }

    public function Shipping($order_id, $merchant_id = 0) {
        $shipping_charge = 0;
        if (isset($order_id) && $order_id != 0 && $order_id != "") {
            $orderShippingQuery = \common\models\OrderShippingCharge::find()->where(['order_id' => $order_id]);
            if ($merchant_id != 0) {
                $orderShippingQuery->andWhere(['merchant_id' => $merchant_id]);
            }
            $orderShippings = $orderShippingQuery->all();
            if ($orderShippings != NULL) {
                foreach ($orderShippings as $orderShipping) {
                    $shipping_charge += $orderShipping->shipping_charge;
                }
            }
        }

        return $shipping_charge;
    }

    public function OrderLog($order, $keys, $template) {
        $template_string = $this->getTemplate($template);
        $errors = [];
        if ($template_string != "") {
            if ($keys != NULL) {
                foreach ($keys as $key => $value) {
                    $template_string = str_replace("%$key%", $value, $template_string);
                }
                $log_text_array = [];
                $result_log = [];
                if ($template_string != "") {
                    $log_text_array = [
                        'text' => $template_string,
                        'timestamp' => date("M d, Y at h:i A"),
                    ];
                    $model = new \common\models\OrderLog();
                    $check_exist = \common\models\OrderLog::findOne(['order_id' => $order->id]);
                    if ($check_exist != NULL) {
                        $model = $check_exist;
                        $exist_log = unserialize($check_exist->log);
                        $result_log = $exist_log;
                    }
                    $result_log[] = $log_text_array;
                    $model->id = uniqid('ordlog');
                    $model->order_id = $order->id;
                    $model->user_id = $order->user_id;
                    $model->status = $order->user_id;
                    $model->log = serialize($result_log);
                    $model->store = $order->store;
                    $model->created_by_type = 1;
                    $model->updated_by_type = 1;
                    if ($model->save()) {

                    } else {
                        $errors[] = $model->errors;
                    }
                }
            }
        } else {
            $errors[] = "Template Not Exist";
        }
        return $errors;
    }

    private function getTemplate($template) {
        $items = [
            "order_create" => "Order Created and status to %status% by %date%",
            "order_payment" => "Payment to be made upon %payment_method%. Order status changed from %status_from% to %status_to%.",
            "order_status" => "Order status changed from %status_from% to %status_to%.",
            "order_add_products" => "Add new order product %product% with %qty% qty",
            "order_update_products" => "Add update order product %product% with %qty% qty",
            "order_update_address" => "%shipping_address% Address Updated",
            "order_add_address" => "New %shipping_address% Address Added"
        ];
        if (isset($items[$template])) {
            return $items[$template];
        } else {
            return "";
        }
    }

}
