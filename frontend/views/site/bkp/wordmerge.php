<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use dosamigos\ckeditor\CKEditor;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

echo '<label class="control-label">Upload Document</label>';
echo FileInput::widget([
    'name' => 'attachment_3',
    'options' => ['multiple' => true]
]);
?>


<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>


<?=

$form->field($model, 'address')->widget(CKEditor::className(), [
    'options' => ['rows' => 6],
    'preset' => 'basic'
])
?>
<?php ActiveForm::end(); ?>
