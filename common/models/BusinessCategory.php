<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "business_category".
 *
 * @property int $id
 * @property int|null $parent
 * @property string $category_name_ar
 * @property string $canonical_name
 * @property string $image
 * @property int $sort_order
 * @property int $status
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * @property string $category_name_en
 */
class BusinessCategory extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'business_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['parent', 'sort_order', 'status', 'created_by', 'updated_by'], 'integer'],
            [['category_name_ar', 'canonical_name', 'status', 'created_by', 'updated_by', 'category_name_en'], 'required'],
            [['created_at', 'updated_at', 'title'], 'safe'],
            [['category_name_ar'], 'string', 'max' => 200],
            [['canonical_name', 'category_name_en'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'parent' => 'Parent',
            'category_name_ar' => 'Category Name Ar',
            'canonical_name' => 'Canonical Name',
            'image' => 'Image',
            'sort_order' => 'Sort Order',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'category_name_en' => 'Category Name En',
        ];
    }

}
