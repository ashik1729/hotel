<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Users;

/**
 * UsersSearch represents the model behind the search form of `common\models\Users`.
 */
class UsersSearch extends Users {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'status'], 'integer'],
            [['name', 'auth_key', 'password', 'password_reset_token', 'email', 'created_at', 'updated_at', 'phone', 'image', 'profession', 'last_login', 'real_ip', 'account_type_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Users::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'account_type_id' => $this->account_type_id,
//            'last_login' => $this->last_login,
        ]);
        if (yii::$app->user->identity->role == 8) {
            $query->andWhere(['account_type_id' => 3]);
            $query->andWhere(['created_by_type' => 0]);
            $query->andWhere(['created_by' => yii::$app->user->identity->id]);
        }


        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'auth_key', $this->auth_key])
                ->andFilterWhere(['like', 'password', $this->password])
                ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'phone', $this->phone]);
//                ->andFilterWhere(['like', 'status', 10]);

        return $dataProvider;
    }

    public function searchDoctor($params) {
        $query = Users::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'role' => $this->role,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
//            'last_login' => $this->last_login,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'auth_key', $this->auth_key])
                ->andFilterWhere(['like', 'password', $this->password])
                ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'phone', $this->phone])
                ->andFilterWhere(['like', 'role', 2]);

        return $dataProvider;
    }

}
