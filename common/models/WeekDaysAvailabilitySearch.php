<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WeekDaysAvailability;

/**
 * WeekDaysAvailabilitySearch represents the model behind the search form of `common\models\WeekDaysAvailability`.
 */
class WeekDaysAvailabilitySearch extends WeekDaysAvailability {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'merchant_id', 'status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'slot_interval', 'availability'], 'integer'],
            [['day', 'available_from', 'available_to', 'created_at', 'updated_at', 'date'], 'safe'],
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

        $query = WeekDaysAvailability::find();

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
        $query->andWhere('date IS NOT NULL');
        $query->orderBy([
            'date' => SORT_DESC
        ]);
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'merchant_id' => $this->merchant_id,
            'available_from' => $this->available_from,
            'available_to' => $this->available_to,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_by_type' => $this->created_by_type,
            'updated_by_type' => $this->updated_by_type,
            'date' => $this->date,
            'slot_interval' => $this->slot_interval,
            'availability' => $this->availability,
        ]);

        $query->andFilterWhere(['like', 'day', $this->day]);

        return $dataProvider;
    }

}
