<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FlightRequest;

/**
 * FlightRequestSearch represents the model behind the search form of `common\models\FlightRequest`.
 */
class FlightRequestSearch extends FlightRequest
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'no_adult', 'no_children', 'no_room', 'purpose', 'status'], 'integer'],
            [['from_place', 'checkin_date', 'return_date', 'class', 'name', 'email', 'phone', 'to_place'], 'safe'],
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
        $query = FlightRequest::find();

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
            'return_date' => $this->return_date,
            'no_adult' => $this->no_adult,
            'no_children' => $this->no_children,
            'no_room' => $this->no_room,
            'purpose' => $this->purpose,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'from_place', $this->from_place])
            ->andFilterWhere(['like', 'class', $this->class])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'to_place', $this->to_place]);

        return $dataProvider;
    }
}
