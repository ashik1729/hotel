<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\LanguageData;

/**
 * LanguageDataSearch represents the model behind the search form of `common\models\LanguageData`.
 */
class LanguageDataSearch extends LanguageData
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type', 'lang_id', 'item_id', 'created_by', 'created_by_type', 'updated_by', 'updated_by_type', 'status'], 'integer'],
            [['value', 'created_at', 'updated_at'], 'safe'],
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
        $query = LanguageData::find();

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
            'type' => $this->type,
            'lang_id' => $this->lang_id,
            'item_id' => $this->item_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'created_by_type' => $this->created_by_type,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'updated_by_type' => $this->updated_by_type,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'value', $this->value]);

        return $dataProvider;
    }
}
