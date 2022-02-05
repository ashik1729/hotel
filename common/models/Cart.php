<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cart".
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property int $options
 * @property int $quantity
 * @property int $created_by
 * @property string $created_at
 * @property int $updated_by
 * @property string $updated_at
 * @property int $status
 *
 * @property User $user
 * @property ProductsServices $product
 */
class Cart extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'cart';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['user_id', 'product_id', 'quantity', 'status'], 'required', 'on' => 'create_cart'],
            [['product_id', 'quantity', 'status'], 'required', 'on' => 'admin_create_cart'],
            [['user_id', 'product_id', 'quantity', 'created_by', 'updated_by', 'status'], 'integer'],
            [['created_by', 'created_at', 'updated_by', 'updated_at', 'created_by_type', 'updated_by_type', 'date', 'booking_slot', 'options', 'session_id','no_adults','no_children','price','coupon_code','coupon_price'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductsServices::className(), 'targetAttribute' => ['product_id' => 'id']],
            ['options', 'checkAttributeSelect'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'product_id' => 'Product ID',
            'options' => 'Options',
            'quantity' => 'Quantity',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }

    /*
     * Checking All Atribute option select(if attributes exist) on add to cart
     */

    public function checkAttributeSelect($attribute, $params) {
        $current_options = explode(',', $this->options);
        $get_attributes = $this->getProductAttributes($this->product_id);
        $count = 0;
        $result_count = 0;

        if ($get_attributes != NULL) {
            $count = count($get_attributes);
            if ($current_options != NULL) {
                foreach ($current_options as $current_option) {
                    $get_product_attribute = ProductAttributesValue::findOne(['id' => $current_option]);
                    if ($get_product_attribute != NULL) {
                        $parent_id = $get_product_attribute->attributesValue->attributes_id;
                        foreach ($get_attributes as $get_attribute) {

                            if ($parent_id = $get_attribute['attribute_id']) {
                                if (isset($get_attribute['attr_items']) && $get_attribute['attr_items'] != NULL) {
                                    foreach ($get_attribute['attr_items'] as $attr_items) {
                                        if ($current_option == $attr_items['id']) {
                                            $result_count++;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($result_count != $count) {
            $this->addError($attribute, 'Select At Least one attributes from each Attribute list');
            return false;
        }
    }

    public function getProductAttributes($product_id) {
        $attributes = [];

        $get_attributes = \common\models\ProductAttributesValue::find()
                        ->select("product_attributes_value.id,attributes_value_id,price,attributes_value.value as attributes_value,attributes.name as name,attributes.id as attributes_id")
                        ->where(['product_attributes_value.status' => 1, 'product_attributes_value.product_id' => $product_id])
                        ->innerJoinWith('attributesValue', false)
                        ->join('LEFT OUTER JOIN', 'attributes', 'attributes_value.attributes_id =attributes.id')
                        ->orderBy(['product_attributes_value.sort_order' => SORT_ASC])
                        ->asArray()->all();
        $attributes_lists = array_unique(array_column($get_attributes, 'attributes_id'));

        if ($attributes_lists != NULL) {

            foreach ($attributes_lists as $attributes_list) {
                $product_attr_items = [];
                foreach ($get_attributes as $get_attribute) {
                    if ($attributes_list == $get_attribute['attributes_id']) {
                        array_push($product_attr_items, $get_attribute);
                        $name = $get_attribute['name'];
                    }
                }
                array_push($attributes, ['attribute_id' => $attributes_list, 'attr_items' => $product_attr_items]);
            }
        }
        return $attributes;
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct() {
        return $this->hasOne(ProductsServices::className(), ['id' => 'product_id']);
    }

    public function getAttr($id) {
        return ProductAttributesValue::findOne(['id' => $id]);
    }

}
