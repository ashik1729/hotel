<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_admin_role_location".
 *
 * @property int $id
 * @property int $role_id
 * @property int $status 1=Enable,0=Desable
 * @property string $created_at
 * @property string $updated_at
 * @property string $controller
 * @property int $role_list_id
 * @property int $created_by
 * @property int $updated_by
 */
class AdminRoleLocation extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'admin_role_location';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['role_id', 'status', 'controller', 'role_list_id', 'created_by', 'updated_by'], 'required'],
            [['role_id', 'status', 'role_list_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['controller'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'role_id' => 'Role ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'controller' => 'Controller',
            'role_list_id' => 'Role List ID',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    public function getLocation() {
        return $this->hasOne(AdminRoleList::className(), ['id' => 'role_list_id']);
    }

}
