<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_wishlist".
 *
 * @property int $id
 * @property int $user_id
 * @property int $favourite_for_id
 * @property string $created_at
 * @property string $updated_at
 * @property int $status
 *
 * @property User $user
 * @property ProductsServices $product
 */
class Favorites extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'user_wishlist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['user_id', 'favourite_for_id', 'favourite_type'], 'required'],
            [['user_id', 'favourite_for_id', 'status', 'favourite_type'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
                //  [['favourite_for_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductsServices::className(), 'targetAttribute' => ['favourite_for_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'favourite_for_id' => 'Favorite For ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct() {
        return $this->hasOne(ProductsServices::className(), ['id' => 'favourite_for_id']);
    }

    public function getMerchant() {
        return $this->hasOne(Merchant::className(), ['id' => 'favourite_for_id']);
    }

}
