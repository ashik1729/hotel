<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cms_content".
 *
 * @property int $id
 * @property string $page_id
 * @property int $type
 * @property string|null $title
 * @property string|null $subtitle
 * @property string $short_description
 * @property string|null $long_description
 * @property string|null $image
 * @property int $status
 * @property string $gallery
 */
class CmsContent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cms_content';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['page_id', 'title', 'status'], 'required'],
            [['id', 'type', 'status'], 'integer'],
            [['short_description', 'long_description', 'gallery'], 'string'],
            [['page_id', 'title', 'subtitle'], 'string', 'max' => 250],
            [['image'], 'string', 'max' => 300],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'page_id' => 'Page ID',
            'type' => 'Type',
            'title' => 'Title',
            'subtitle' => 'Subtitle',
            'short_description' => 'Short Description',
            'long_description' => 'Long Description',
            'image' => 'Image',
            'status' => 'Status',
            'gallery' => 'Gallery',
        ];
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
        $model = CmsContent::find()->where(['status' => 1, 'id' => $id])->one();
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
