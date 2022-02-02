<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cms_data".
 *
 * @property int $id
 * @property int $page_id
 * @property string $can_name
 * @property string $title
 * @property string $file
 * @property string $gallery
 * @property string $field_one
 * @property string $field_two
 * @property string $field_three
 * @property string $field_four
 * @property int|null $status
 * @property int $sort_order
 *
 * @property CmsContent $page
 */
class CmsData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cms_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['page_id', 'can_name', 'title'], 'required'],
            [['file', 'gallery', 'field_one', 'field_two', 'field_three', 'field_four', 'sort_order'], 'safe'],
            [['page_id', 'status', 'sort_order'], 'integer'],
            [['gallery', 'field_one', 'field_two', 'field_three', 'field_four'], 'string'],
            [['can_name'], 'string', 'max' => 255],
            [['title'], 'string', 'max' => 200],
            [['file'], 'string', 'max' => 100],
            [['page_id'], 'exist', 'skipOnError' => true, 'targetClass' => CmsContent::className(), 'targetAttribute' => ['page_id' => 'id']],
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
            'can_name' => 'Can Name',
            'title' => 'Title',
            'file' => 'File',
            'gallery' => 'gallery',
            'field_one' => 'Field One',
            'field_two' => 'Field Two',
            'field_three' => 'Field Three',
            'field_four' => 'Field Four',
            'status' => 'Status',
            'sort_order' => 'Sort Order',
        ];
    }

    /**
     * Gets query for [[Page]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(CmsContent::className(), ['id' => 'page_id']);
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
        $model = CmsData::find()->where(['status' => 1, 'id' => $id])->one();
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
