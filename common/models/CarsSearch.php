<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Cars;

/**
 * CarsSearch represents the model behind the search form of `common\models\Cars`.
 */
class CarsSearch extends Cars
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'brand', 'type_of_car', 'status', 'sort_order'], 'integer'],
            [['title', 'long_description', 'short_description', 'image', 'gallery', 'model_year', 'series'], 'safe'],
            [['day_price', 'day_offer', 'week_price', 'week_offer', 'month_price', 'month_offer'], 'number'],
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
        $query = Cars::find();

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
            'brand' => $this->brand,
            'type_of_car' => $this->type_of_car,
            'model_year' => $this->model_year,
            'day_price' => $this->day_price,
            'day_offer' => $this->day_offer,
            'week_price' => $this->week_price,
            'week_offer' => $this->week_offer,
            'month_price' => $this->month_price,
            'month_offer' => $this->month_offer,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'long_description', $this->long_description])
            ->andFilterWhere(['like', 'short_description', $this->short_description])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'gallery', $this->gallery])
            ->andFilterWhere(['like', 'series', $this->series]);

        return $dataProvider;
    }
}
