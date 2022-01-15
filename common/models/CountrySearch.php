<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Country;

/**
 * CountrySearch represents the model behind the search form of `common\models\Country`.
 */
class CountrySearch extends Country
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_name', 'iso', 'created_at', 'updated_at', 'iso3', 'country_name_ar'], 'safe'],
            [['status', 'id', 'numcode', 'phonecode'], 'integer'],
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
        $query = Country::find();

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
            'id' => $this->id,
            'numcode' => $this->numcode,
            'phonecode' => $this->phonecode,
        ]);

        $query->andFilterWhere(['like', 'country_name', $this->country_name])
            ->andFilterWhere(['like', 'iso', $this->iso])
            ->andFilterWhere(['like', 'iso3', $this->iso3])
            ->andFilterWhere(['like', 'country_name_ar', $this->country_name_ar]);

        return $dataProvider;
    }
}
