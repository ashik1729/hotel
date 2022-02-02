<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CmsData;

/**
 * CmsDataSearch represents the model behind the search form of `common\models\CmsData`.
 */
class CmsDataSearch extends CmsData
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'page_id', 'status', 'sort_order'], 'integer'],
            [['can_name', 'title', 'file', 'gallery', 'field_one', 'field_two', 'field_three', 'field_four'], 'safe'],
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
        $query = CmsData::find();

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
            'page_id' => $this->page_id,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
        ]);

        $query->andFilterWhere(['like', 'can_name', $this->can_name])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'file', $this->file])
            ->andFilterWhere(['like', 'gallery', $this->gallery])
            ->andFilterWhere(['like', 'field_one', $this->field_one])
            ->andFilterWhere(['like', 'field_two', $this->field_two])
            ->andFilterWhere(['like', 'field_three', $this->field_three])
            ->andFilterWhere(['like', 'field_four', $this->field_four]);

        return $dataProvider;
    }
}
