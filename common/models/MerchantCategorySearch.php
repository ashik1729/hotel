<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MerchantCategory;

/**
 * MerchantCategorySearch represents the model behind the search form of `common\models\MerchantCategory`.
 */
class MerchantCategorySearch extends MerchantCategory {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'status', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'sort_order'], 'integer'],
            [['name', 'name_ar', 'description', 'description_ar', 'image', 'created_at', 'updatet_at'], 'safe'],
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
        $query = MerchantCategory::find();

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
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updatet_at' => $this->updatet_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_by_type' => $this->created_by_type,
            'updated_by_type' => $this->updated_by_type,
            'sort_order' => $this->sort_order,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'name_ar', $this->name_ar])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'description_ar', $this->description_ar])
                ->andFilterWhere(['like', 'image', $this->image]);

        return $dataProvider;
    }

}
