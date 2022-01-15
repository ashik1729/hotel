<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MerchantFeatureList;

/**
 * MerchantFeatureListSearch represents the model behind the search form of `common\models\MerchantFeatureList`.
 */
class MerchantFeatureListSearch extends MerchantFeatureList {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'merchant_id', 'status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'sort_order', 'feature_id'], 'integer'],
            [['value_en', 'value_ar', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = MerchantFeatureList::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (\Yii::$app->user->identity->interface == 'merchant') {
            $query->andFilterWhere([
                'merchant_id' => \Yii::$app->user->identity->id,
            ]);
        }
        if (\Yii::$app->user->identity->interface == 'franchise') {
            $get_merchant = Merchant::find()->select('id')->where(['franchise_id' => \Yii::$app->user->identity->id])->asArray()->all();
            $merchant_array = array_column($get_merchant, 'id');
            $query->andFilterWhere([
                'merchant_id' => $merchant_array,
            ]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'merchant_id' => $this->merchant_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'feature_id' => $this->feature_id,
            'created_by_type' => $this->created_by_type,
            'updated_by_type' => $this->updated_by_type,
            'sort_order' => $this->sort_order,
        ]);

        $query->andFilterWhere(['like', 'value_en', $this->value_en])
                ->andFilterWhere(['like', 'value_ar', $this->value_ar]);

        return $dataProvider;
    }

}
