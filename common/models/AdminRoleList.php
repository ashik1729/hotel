<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_admin_role_list".
 *
 * @property int $id
 * @property string $name
 * @property string $action
 * @property int $status
 * @property string $created_at
 * @property string $update_at
 * @property string $controller
 * @property int $created_by
 * @property int $updated_by
 */
class AdminRoleList extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'admin_role_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'action', 'status', 'controller', 'created_by', 'updated_by'], 'required'],
            [['status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'update_at'], 'safe'],
            [['name', 'action'], 'string', 'max' => 50],
            [['controller'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'action' => 'Action',
            'status' => 'Status',
            'created_at' => 'Created At',
            'update_at' => 'Update At',
            'controller' => 'Controller',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

}
