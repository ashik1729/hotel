<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MarketingNotification;

/**
 * MarketingNotificationSearch represents the model behind the search form of `common\models\MarketingNotification`.
 */
class MarketingNotificationSearch extends MarketingNotification
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'notification_type'], 'integer'],
            [['title_ar', 'title_en', 'description_en', 'description_ar', 'file', 'created_at', 'updated_at', 'user_group', 'user', 'link'], 'safe'],
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
        $query = MarketingNotification::find();

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
            'updated_at' => $this->updated_at,
            'notification_type' => $this->notification_type,
        ]);

        $query->andFilterWhere(['like', 'title_ar', $this->title_ar])
            ->andFilterWhere(['like', 'title_en', $this->title_en])
            ->andFilterWhere(['like', 'description_en', $this->description_en])
            ->andFilterWhere(['like', 'description_ar', $this->description_ar])
            ->andFilterWhere(['like', 'file', $this->file])
            ->andFilterWhere(['like', 'user_group', $this->user_group])
            ->andFilterWhere(['like', 'user', $this->user])
            ->andFilterWhere(['like', 'link', $this->link]);

        return $dataProvider;
    }
}
