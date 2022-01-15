<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class SelectCategory extends Component {

    public function selectCategories($data) {
        $html = '';
        $getcatdata = \common\models\Category::find()->where(['id' => $data->id])->one();
        if ($data->id == $data->parent) {
            $html .= $getcatdata->id;
        } else {
            $html .= $getcatdata->id . '-';
            $results = \common\models\Category::find()->where(['id' => $data->parent])->one();
            if ($results != NULL) {
                $html .= $this->selectCategories($results);
            }
        }
        return $html;
    }

    public function selectBusinessCategories($data) {
        $html = '';
        $getcatdata = \common\models\BusinessCategory::find()->where(['id' => $data->id])->one();
        if ($data->id == $data->parent) {
            $html .= $getcatdata->id;
        } else {
            $html .= $getcatdata->id . '-';
            $results = \common\models\BusinessCategory::find()->where(['id' => $data->parent])->one();
            if ($results != NULL) {
                $html .= $this->selectBusinessCategories($results);
            }
        }
        return $html;
    }

    public function selectchildCategories($id) {

        $data = '';
        $getcatdata = \common\models\Category::find()->where(['parent' => $id])->all();

        if ($getcatdata != NULL) {
            foreach ($getcatdata as $getcatdt) {
                if ($getcatdt->id != $id) {
                    $data .= $getcatdt->id . ',';
                    $datas = $this->childcat($getcatdt->id);
                    $data .= $datas;
//                    array_merge($data, $datas);
                } else {
                    $data .= $getcatdt->id . ',';
                }
            }
        }
        return $data;
    }

    function childcat($id) {


        $data = '';

        $getcatdata = \common\models\Category::find()->where(['parent' => $id])->all();
        if ($getcatdata != NULL) {
            foreach ($getcatdata as $getcatdt) {
                $data .= $getcatdt->id . ',';
                $datas = $this->childcat($getcatdt->id);
                $data .= $datas . ',';
                return $data;
            }
        } else {
            return $data;
        }
    }

}
