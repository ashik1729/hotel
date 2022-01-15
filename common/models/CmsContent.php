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
            [['id', 'page_id', 'type', 'short_description', 'status', 'gallery'], 'required'],
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
}
