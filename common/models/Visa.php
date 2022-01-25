<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "visa".
 *
 * @property int $id
 * @property string $title
 * @property string|null $subtitle
 * @property string|null $short_description
 * @property string|null $long_description
 * @property string|null $image
 * @property string|null $gallery
 * @property int $status
 * @property int|null $sort_order
 * @property string $can_name
 */
class Visa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'status', 'can_name', 'image', 'short_description','price'], 'required'],
            [['title', 'subtitle', 'short_description', 'long_description', 'gallery', 'can_name'], 'string'],
            [['status', 'sort_order'], 'integer'],
            [['image'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'subtitle' => 'Subtitle',
            'short_description' => 'Short Description',
            'long_description' => 'Long Description',
            'image' => 'Image',
            'gallery' => 'Gallery',
            'status' => 'Status',
            'sort_order' => 'Sort Order',
            'can_name' => 'Can Name',
        ];
    }
    public function uploadFile($file, $name, $folder)
    {

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

    public function uploadMultipleImage($uploadfile, $id, $name, $foldername = false)
    {
        $model = Visa::find()->where(['status' => 1, 'id' => $id])->one();
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
