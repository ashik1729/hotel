<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ProductsServices;

/**
 * ProductsServicesSearch represents the model behind the search form of `common\models\ProductsServices`.
 */
class ProductsServicesSearch extends ProductsServices {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
           // [['id', 'category_id', 'merchant_id', 'sort_order', 'discount_type', 'requires_shipping', 'stock_availability', 'is_featured', 'is_admin_approved', 'updated_by', 'created_by', 'status', 'tax_applicable', 'min_quantity', 'quantity', 'weight_class', 'type'], 'integer'],
            [['sku', 'product_name_en', 'canonical_name', 'image', 'gallery', 'new_from', 'new_to', 'sale_from', 'sale_to', 'discount_from', 'discount_to', 'search_tag', 'related_products', 'created_at', 'updated_at', 'meta_title', 'meta_description', 'meta_keywords', 'short_description_en', 'long_description_en', 'title', 'discount_id', 'store'], 'safe'],
            [['price', 'discount_rate'], 'number'],
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
        $query = ProductsServices::find();

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
        if (\Yii::$app->user->identity->interface == 'merchant') {
            $query->andFilterWhere([
                'merchant_id' => \Yii::$app->user->identity->id,
            ]);
        }
        if (\Yii::$app->user->identity->interface == 'franchise') {
            $get_merchant = Merchant::find()->select('id')->where(['franchise_id' => \Yii::$app->user->identity->id])->asArray()->all();
            $merchant_array = array_column($get_merchant, 'id');
            $query->andFilterWhere([
                'merchant_id' => $merchant_array,
            ]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            // 'merchant_id' => $this->merchant_id,
            'sort_order' => $this->sort_order,
            'price' => $this->price,
            'discount_type' => $this->discount_type,
            'discount_rate' => $this->discount_rate,
            // 'requires_shipping' => $this->requires_shipping,
            // 'new_from' => $this->new_from,
            //'new_to' => $this->new_to,
          //  'sale_from' => $this->sale_from,
          //  'sale_to' => $this->sale_to,
            //'discount_from' => $this->discount_from,
            //'discount_id' => $this->discount_id,
            //'discount_to' => $this->discount_to,
          //  'stock_availability' => $this->stock_availability,
          //  'is_featured' => $this->is_featured,
          //  'is_admin_approved' => $this->is_admin_approved,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'created_by' => $this->created_by,
            'status' => $this->status,
        //    'tax_applicable' => $this->tax_applicable,
           // 'tax_amount' => $this->tax_amount,
          //  'min_quantity' => $this->min_quantity,
            'quantity' => $this->quantity,
          //  'weight_class' => $this->weight_class,
            // 'weight' => $this->weight,
           // 'type' => $this->type,
        ]);

        $query
                //->andFilterWhere(['like', 'sku', $this->sku])
                ->andFilterWhere(['like', 'product_name_en', $this->product_name_en])
                //->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'canonical_name', $this->canonical_name])
                ->andFilterWhere(['like', 'image', $this->image])
                ->andFilterWhere(['like', 'gallery', $this->gallery])
                ->andFilterWhere(['like', 'search_tag', $this->search_tag])
            //    ->andFilterWhere(['like', 'related_products', $this->related_products])
                ->andFilterWhere(['like', 'meta_title', $this->meta_title])
                ->andFilterWhere(['like', 'meta_description', $this->meta_description])
                ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
                ->andFilterWhere(['like', 'short_description_en', $this->short_description_en])
                ->andFilterWhere(['like', 'long_description_en', $this->long_description_en]);

        if (isset($this->store) && $this->store != '') {
            $get_merchants = Merchant::find()->select('id')->where(['franchise_id' => $this->store])->all();
//            if ($get_merchants != NULL) {
            $merchant_list = array_column($get_merchants, 'id');
//            if ($merchant_list != NULL) {
            $query->andWhere(['in', 'merchant_id', $merchant_list]);
//                }
//            }
        }
        return $dataProvider;
    }

}
