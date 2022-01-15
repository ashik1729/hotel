<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ErrorCode;

/**
 * ErrorCodeSearch represents the model behind the search form of `common\models\ErrorCode`.
 */
class ErrorCodeSearch extends ErrorCode
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'error_code', 'status'], 'integer'],
            [['error_title', 'error_en', 'error_ar', 'created_at', 'updated_at'], 'safe'],
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
        $query = ErrorCode::find();

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
            'error_code' => $this->error_code,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'error_title', $this->error_title])
            ->andFilterWhere(['like', 'error_en', $this->error_en])
            ->andFilterWhere(['like', 'error_ar', $this->error_ar]);

        return $dataProvider;
    }
}
