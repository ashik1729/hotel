<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_admin_role".
 *
 * @property int $id
 * @property string $role_name
 * @property int $status 1=Enable;2=Desable
 * @property string $created_at
 * @property string $updated_at
 */
class AdminRole extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'admin_role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['role_name', 'status'], 'required'],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['role_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'role_name' => 'Role Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}
