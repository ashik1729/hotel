<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Orders;

/**
 * OrdersSearch represents the model behind the search form of `common\models\Orders`.
 */
class OrdersSearch extends Orders {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'user_id', 'shipping_method', 'ship_address', 'bill_address', 'payment_method', 'payment_status', 'status', 'created_by', 'updated_by'], 'integer'],
            [['transaction_id', 'customer_comment', 'admin_comment', 'created_at', 'updated_at'], 'safe'],
            [['total_amount', 'shipping_charge'], 'number'],
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
        $query = Orders::find();
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
            'user_id' => $this->user_id,
            'shipping_method' => $this->shipping_method,
            'ship_address' => $this->ship_address,
            'bill_address' => $this->bill_address,
            'total_amount' => $this->total_amount,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'shipping_charge' => $this->shipping_charge,
        ]);

        $query->andFilterWhere(['like', 'transaction_id', $this->transaction_id])
                ->andFilterWhere(['like', 'customer_comment', $this->customer_comment])
                ->andFilterWhere(['like', 'admin_comment', $this->admin_comment]);

        return $dataProvider;
    }

}
