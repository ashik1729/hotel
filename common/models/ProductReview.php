<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_review".
 *
 * @property int $id
 * @property int $user_id
 * @property int $review_for_id
 * @property int $rating
 * @property string $comment
 * @property int $approvel
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property User $user
 * @property ProductsServices $product
 */
class ProductReview extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'product_review';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['user_id', 'review_for_id', 'rating', 'review_type', 'created_by', 'updated_by','author'], 'required'],
            [['user_id', 'review_for_id', 'rating', 'approvel', 'created_by', 'updated_by'], 'integer'],
            [['comment'], 'string'],
            [['created_at', 'updated_at', 'comment', 'created_at', 'updated_at', 'designation'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
//            [['review_for_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductsServices::className(), 'targetAttribute' => ['review_for_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'review_for_id' => 'Product ID',
            'rating' => 'Rating',
            'comment' => 'Comment',
            'review_type' => 'Review Type',
            'approvel' => 'Approvel',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
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
        return $this->hasOne(ProductsServices::className(), ['id' => 'review_for_id']);
    }

    public function getMerchant() {
        return $this->hasOne(Merchant::className(), ['id' => 'review_for_id']);
    }

}
