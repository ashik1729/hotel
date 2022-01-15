<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OrderProducts;

/**
 * OrderProductsSearch represents the model behind the search form of `common\models\OrderProducts`.
 */
class OrderProductsSearch extends OrderProducts {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'order_id', 'user_id', 'product_id', 'merchant_id', 'quantity', 'status', 'created_by', 'updated_by'], 'integer'],
            [['amount'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
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
    public function search($params, $order_id) {
        $query = OrderProducts::find()->where(['order_id' => $order_id]);
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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'order_id' => $this->order_id,
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
            'merchant_id' => $this->merchant_id,
            'quantity' => $this->quantity,
            'amount' => $this->amount,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        return $dataProvider;
    }

}
