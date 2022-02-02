<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AccomodationRequest;

/**
 * AccomodationRequestSearch represents the model behind the search form of `common\models\AccomodationRequest`.
 */
class AccomodationRequestSearch extends AccomodationRequest
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'no_adult', 'no_children', 'no_room', 'accomodation', 'purpose', 'status'], 'integer'],
            [['destination', 'checkin_date', 'name', 'email', 'phone'], 'safe'],
            [['checkout_date'], 'number'],
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
        $query = AccomodationRequest::find();

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
            'checkin_date' => $this->checkin_date,
            'checkout_date' => $this->checkout_date,
            'no_adult' => $this->no_adult,
            'no_children' => $this->no_children,
            'no_room' => $this->no_room,
            'accomodation' => $this->accomodation,
            'purpose' => $this->purpose,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'destination', $this->destination])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone]);

        return $dataProvider;
    }
}
