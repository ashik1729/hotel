<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class Currency extends Component {

    public function convert($amount, $store, $lang = 1) {
        $return_amount = $amount;
        if ($amount >= 0) {
            if ($store == 0) {

            } else if ($store > 0) {
                $get_store = \common\models\Franchise::findOne(['id' => $store]);
                if ($get_store != NULL) {
                    $get_currency = $lang == 1 ? $get_store->currency0->shortcode : $get_store->currency0->shortcode_ar;
                    $get_position = $get_store->currency0->position;
                    if ($get_position == 1) {
                        $return_amount = $get_currency . ' ' . $amount;
                    } else {
                        $return_amount = $amount . ' ' . $get_currency;
                    }
                }
            }
        }
        return $return_amount;
    }

}
