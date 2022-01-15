<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Merchant;

/**
 * MerchantSearch represents the model behind the search form of `common\models\Merchant`.
 */
class MerchantSearch extends Merchant {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'gender', 'country', 'state', 'city', 'status', 'newsletter', 'emailverify', 'created_by', 'created_by_type', 'updated_by', 'updated_by_type', 'shipping_type', 'payment_type', 'franchise_id'], 'integer'],
            [['first_name', 'last_name', 'dob', 'email', 'password', 'password_reset_token', 'profile_image', 'mobile_number', 'address', 'auth_key', 'created_at', 'updated_at', 'user_otp', 'business_name', 'business_logo', 'business_gallery', 'alternative_email_address', 'return_policy', 'shipping_terms'], 'safe'],
            [['rating'], 'number'],
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
        $query = Merchant::find();

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
        if (\Yii::$app->user->identity->interface == 'franchise') {
            $query->andFilterWhere([
                'franchise_id' => \Yii::$app->user->identity->id,
            ]);
        }


//        echo '<pre/>';
//        print_r($query);
//        exit;
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'gender' => $this->gender,
            'dob' => $this->dob,
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
            'newsletter' => $this->newsletter,
            'emailverify' => $this->emailverify,
            'created_by' => $this->created_by,
            'created_by_type' => $this->created_by_type,
            'updated_by' => $this->updated_by,
            'updated_by_type' => $this->updated_by_type,
            'shipping_type' => $this->shipping_type,
            'payment_type' => $this->payment_type,
            'rating' => $this->rating,
            'franchise_id' => $this->franchise_id,
        ]);

        $query->andFilterWhere(['like', 'first_name', $this->first_name])
                ->andFilterWhere(['like', 'last_name', $this->last_name])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'password', $this->password])
                ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
                ->andFilterWhere(['like', 'profile_image', $this->profile_image])
                ->andFilterWhere(['like', 'mobile_number', $this->mobile_number])
                ->andFilterWhere(['like', 'address', $this->address])
                ->andFilterWhere(['like', 'auth_key', $this->auth_key])
                ->andFilterWhere(['like', 'user_otp', $this->user_otp])
                ->andFilterWhere(['like', 'business_name', $this->business_name])
                ->andFilterWhere(['like', 'business_logo', $this->business_logo])
                ->andFilterWhere(['like', 'business_gallery', $this->business_gallery])
                ->andFilterWhere(['like', 'alternative_email_address', $this->alternative_email_address])
                ->andFilterWhere(['like', 'return_policy', $this->return_policy])
                ->andFilterWhere(['like', 'shipping_terms', $this->shipping_terms]);
        if (isset($this->first_name) && $this->first_name != '') {
            $query->andFilterWhere(['like', 'first_name', $this->first_name]);
            $query->orFilterWhere(['like', 'last_name', $this->first_name]);
        }
        return $dataProvider;
    }

}
