<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Category;

/**
 * CategorySearch represents the model behind the search form of `common\models\Category`.
 */
class CategorySearch extends Category {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'parent', 'sort_order', 'header_visibility', 'status', 'created_by', 'updated_by'], 'integer'],
            [['category_name', 'description', 'canonical_name', 'image', 'gallery', 'search_tag', 'created_at', 'updated_at', 'meta_title', 'meta_description', 'meta_keywords'], 'safe'],
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
        $query = Category::find();

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
            'sort_order' => $this->sort_order,
            'header_visibility' => $this->header_visibility,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);
        if ($this->parent != '') {
            $option_items = \Yii::$app->SelectCategory->selectchildCategories($this->parent);
            $catlist = array_filter(explode(',', $option_items));
//            if ($catlist != NULL) {
            $query->andWhere(['parent' => $catlist]);
//            }
        } else {
            $query->andFilterWhere([
                'parent' => $this->parent
            ]);
        }
        $query->andFilterWhere(['like', 'category_name', $this->category_name])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'canonical_name', $this->canonical_name])
                ->andFilterWhere(['like', 'image', $this->image])
                ->andFilterWhere(['like', 'gallery', $this->gallery])
                ->andFilterWhere(['like', 'search_tag', $this->search_tag])
                ->andFilterWhere(['like', 'meta_title', $this->meta_title])
                ->andFilterWhere(['like', 'meta_description', $this->meta_description])
                ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords]);

        return $dataProvider;
    }

    public function array_flatten($array) {
        if (!is_array($array)) {
            return FALSE;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $this->array_flatten($value));
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }

}
