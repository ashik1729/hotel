<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cars".
 *
 * @property int $id
 * @property string|null $title
 * @property int $brand
 * @property int $type_of_car
 * @property string $long_description
 * @property string $short_description
 * @property string $image
 * @property string $gallery
 * @property string|null $model_year
 * @property string|null $series
 * @property float $day_price
 * @property float $day_offer
 * @property float $week_price
 * @property float $week_offer
 * @property float $month_price
 * @property float $month_offer
 * @property int $status
 * @property int|null $sort_order
 *
 * @property Brands $brand0
 * @property TypeOfCar $typeOfCar
 * @property RentalEnquiry[] $rentalEnquiries
 */
class Cars extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cars';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'long_description', 'short_description', 'gallery'], 'string'],
            [['brand', 'type_of_car', 'short_description', 'image', 'day_price', 'day_offer', 'week_price', 'week_offer', 'month_price', 'month_offer', 'status','can_name'], 'required'],
            [['brand', 'type_of_car', 'status', 'sort_order'], 'integer'],
            [['model_year'], 'safe'],
            [['day_price', 'day_offer', 'week_price', 'week_offer', 'month_price', 'month_offer'], 'number'],
            [['image', 'series'], 'string', 'max' => 200],
            [['brand'], 'exist', 'skipOnError' => true, 'targetClass' => Brands::className(), 'targetAttribute' => ['brand' => 'id']],
            [['type_of_car'], 'exist', 'skipOnError' => true, 'targetClass' => TypeOfCar::className(), 'targetAttribute' => ['type_of_car' => 'id']],
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
            'brand' => 'Brand',
            'type_of_car' => 'Type Of Car',
            'long_description' => 'Long Description',
            'short_description' => 'Short Description',
            'image' => 'Image',
            'gallery' => 'Gallery',
            'model_year' => 'Model Year',
            'series' => 'Series',
            'day_price' => 'Day Price',
            'day_offer' => 'Day Offer',
            'week_price' => 'Week Price',
            'week_offer' => 'Week Offer',
            'month_price' => 'Month Price',
            'month_offer' => 'Month Offer',
            'status' => 'Status',
            'sort_order' => 'Sort Order',
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
        $model = Cars::find()->where(['status' => 1, 'id' => $id])->one();
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
    /**
     * Gets query for [[Brand0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBrand0()
    {
        return $this->hasOne(Brands::className(), ['id' => 'brand']);
    }

    /**
     * Gets query for [[TypeOfCar]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTypeOfCar()
    {
        return $this->hasOne(TypeOfCar::className(), ['id' => 'type_of_car']);
    }

    /**
     * Gets query for [[RentalEnquiries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRentalEnquiries()
    {
        return $this->hasMany(RentalEnquiry::className(), ['car_id' => 'id']);
    }
}
