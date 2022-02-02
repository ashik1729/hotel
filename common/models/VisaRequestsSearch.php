<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VisaRequests;

/**
 * VisaRequestsSearch represents the model behind the search form of `common\models\VisaRequests`.
 */
class VisaRequestsSearch extends VisaRequests
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'visa_option', 'user_id', 'processing_type', 'no_visa', 'status','visa_id'], 'integer'],
            [['travel_date_from', 'travel_date_to'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = VisaRequests::find();

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
            'visa_option' => $this->visa_option,
            'visa_id' => $this->visa_id,
            'user_id' => $this->user_id,
            'processing_type' => $this->processing_type,
            'no_visa' => $this->no_visa,
            'travel_date_from' => $this->travel_date_from,
            'travel_date_to' => $this->travel_date_to,
            'status' => $this->status,
        ]);

        return $dataProvider;
    }
}
