<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property int $parent
 * @property string $category_name
 * @property string $description
 * @property string $canonical_name
 * @property string $image
 * @property string $gallery
 * @property string $search_tag
 * @property int $sort_order
 * @property int $header_visibility
 * @property int $status
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 *
 * @property ProductsServices[] $productsServices
 */
class Category extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['category_name', 'canonical_name', 'status', 'category_name_ar'], 'required'],
            [['parent', 'sort_order', 'header_visibility', 'status', 'created_by', 'updated_by'], 'integer'],
            [['description', 'gallery', 'search_tag', 'meta_description', 'meta_keywords'], 'string'],
            [['created_at', 'updated_at', 'parent', 'created_at', 'created_by', 'updated_at', 'updated_by', 'search_tag', 'sort_order', 'header_visibility', 'gallery', 'meta_title', 'meta_description', 'meta_keywords', 'description_ar'], 'safe'],
            [['category_name'], 'string', 'max' => 200],
            [['canonical_name', 'meta_title'], 'string', 'max' => 255],
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
            'category_name' => 'Category Name',
            'description' => 'Description',
            'canonical_name' => 'Canonical Name',
            'image' => 'Image',
            'gallery' => 'Gallery',
            'search_tag' => 'Search Tag',
            'sort_order' => 'Sort Order',
            'header_visibility' => 'Header Visibility',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
        ];
    }

    /**
     * Gets query for [[ProductsServices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductsServices() {
        return $this->hasMany(ProductsServices::className(), ['category_id' => 'id']);
    }

    public function uploadFile($file, $name, $folder) {

        $targetFolder = \yii::$app->basePath . '/../uploads/' . $folder . '/';
        if (!file_exists($targetFolder)) {
            mkdir($targetFolder, 0777, true);
        }
        if ($file->saveAs($targetFolder . $name . '.' . $file->extension)) {
            return true;
        } else {
            return false;
        }
    }

    public function uploadMultipleImage($uploadfile, $id, $name, $foldername = false) {
        $model = Category::find()->where(['status' => 1, 'id' => $id])->one();
        if (!is_dir(Yii::$app->basePath . '/../uploads/' . $foldername)) {
            mkdir(Yii::$app->basePath . '/../uploads/' . $foldername);
            chmod(Yii::$app->basePath . '/../uploads/' . $foldername . '', 0777);
        }
        $i = 1;
        if ($model->gallery != '') {
            $name_array = explode(',', $model->gallery);
        } else {
            $name_array = [];
        }
        foreach ($uploadfile as $upload) {
            if (isset($upload)) {
                $gallery_name = 'gallery' . $name . $i;
                if ($upload->saveAs(Yii::$app->basePath . '/../uploads/' . $foldername . '/' . $gallery_name . '.' . $upload->extension)) {
                    chmod(Yii::$app->basePath . '/../uploads/' . $foldername . '/' . $gallery_name . '.' . $upload->extension, 0777);
                    $name_array[] = $gallery_name . '.' . $upload->extension;
                }
            }
            $i++;
        }



        if ($model != NULL) {

            if ($name_array != NULL) {


                $model->gallery = implode(',', $name_array);
                $model->save(FALSE);
            }
        }
    }

}
