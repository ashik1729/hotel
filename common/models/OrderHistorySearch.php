<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OrderHistory;

/**
 * OrderHistorySearch represents the model behind the search form of `common\models\OrderHistory`.
 */
class OrderHistorySearch extends OrderHistory {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'order_id', 'order_product_id', 'order_status', 'shipping_type', 'status', 'updated_by', 'created_by'], 'integer'],
            [['tracking_id', 'order_status_custome_comment', 'created_at', 'updated_at'], 'safe'],
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
        $query = OrderHistory::find();

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
            'order_product_id' => $this->order_product_id,
            'order_status' => $this->order_status,
            'shipping_type' => $this->shipping_type,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'tracking_id', $this->tracking_id])
                ->andFilterWhere(['like', 'order_status_custome_comment', $this->order_status_custome_comment]);

        return $dataProvider;
    }

}
