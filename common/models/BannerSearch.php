<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Banner;

/**
 * BannerSearch represents the model behind the search form of `common\models\Banner`.
 */
class BannerSearch extends Banner {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'banner_type', 'file_type', 'status', 'created_by', 'updated_by', 'sort_order', 'map_type', 'map_to'], 'integer'],
            [['name', 'file_ios', 'file_and', 'created_at', 'updated_at'], 'safe'],
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
        $query = Banner::find();

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
            'banner_type' => $this->banner_type,
            'file_type' => $this->file_type,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'sort_order' => $this->sort_order,
            'map_type' => $this->map_type,
            'map_to' => $this->map_to,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'description_en', $this->description_en])
                ->andFilterWhere(['like', 'description_ar', $this->description_ar])
                ->andFilterWhere(['like', 'file_and', $this->file_and])
                ->andFilterWhere(['like', 'file_ios', $this->file_ios]);

        return $dataProvider;
    }

}
