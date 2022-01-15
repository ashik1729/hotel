<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ImageAssets;

/**
 * ImageAssetsSearch represents the model behind the search form of `common\models\ImageAssets`.
 */
class ImageAssetsSearch extends ImageAssets {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'type', 'status', 'sort_order', 'created_by', 'updated_by', 'created_by_type', 'updated_by_type', 'device_type', 'description_en', 'description_ar'], 'integer'],
            [['title', 'created_at', 'updated_at', 'image'], 'safe'],
            [['version'], 'number'],
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
        $query = ImageAssets::find();

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
            'status' => $this->status,
            'sort_order' => $this->sort_order,
            'device_type' => $this->device_type,
            'version' => $this->version,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by_type' => $this->created_by_type,
            'updated_by_type' => $this->updated_by_type,
        ]);

        $query->andFilterWhere(['like',
            'title', $this->title,
            'image', $this->image
        ]);

        return $dataProvider;
    }

}
