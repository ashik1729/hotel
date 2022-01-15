<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
/**
 *
 * @package    Material Dashboard Yii2
 * @author     CodersEden <hello@coderseden.com>
 * @link       https://www.coderseden.com
 * @copyright  2020 Material Dashboard Yii2 (https://www.coderseden.com)
 * @license    MIT - https://www.coderseden.com
 * @since      1.0
 */
use yii\helpers\Html;
use yii\widgets\DetailView;
?>

<div class="content">

    <div class="container">
        <h3 class="heading text-center">Chat Bubble</h3>


        <div class="container-fluid">
            <div class="card ">
                <div class="card-header card-header-primary card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">account_box</i>
                    </div>
                    <h4 class="card-title">
                        <?= Html::encode('Support Tickets') ?>

                        <div class="pull-right">
                            <?=
                            Html::a(Html::tag('b', 'keyboard_arrow_left', ['class' => 'material-icons']), ['index'], [
                                'class' => 'btn btn-xs btn-success btn-round btn-fab',
                                'rel' => "tooltip",
                                'data' => [
                                    'placement' => 'bottom',
                                    'original-title' => 'Back'
                                ],
                            ])
                            ?>
                            <?=
                            Html::a(Html::tag('b', 'create', ['class' => 'material-icons']), ['update', 'id' => $model->id], [
                                'class' => 'btn btn-xs btn-success btn-round btn-fab',
                                'rel' => "tooltip",
                                'data' => [
                                    'placement' => 'bottom',
                                    'original-title' => 'Edit User'
                                ],
                            ])
                            ?>
                            <?=
                            Html::a(Html::tag('b', 'delete', ['class' => 'material-icons']), ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-xs btn-danger btn-round btn-fab',
                                'rel' => "tooltip",
                                'onclick' => "return confirm('Are you sure you want to delete this item?')",
                                'data' => [
                                    'confirm' => \Yii::t('app', 'Are you sure you want to delete this item?'),
                                    'method' => 'post',
                                    'placement' => 'bottom',
                                    'original-title' => 'Delete User'
                                ],
                            ])
                            ?>
                        </div>
                    </h4>
                </div>
                <div class="card-body">
                    <?php if (Yii::$app->session->hasFlash("success")): ?>

                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="material-icons">close</i>
                            </button>
                            <span>
                                <?= Yii::$app->session->getFlash("success") ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <?php if (Yii::$app->session->hasFlash("error")): ?>

                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="material-icons">close</i>
                            </button>
                            <span>                <?= Yii::$app->session->getFlash("error") ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    <?=
                    DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            [
                                'attribute' => 'user_id',
                                'header' => 'user_id',
                                'value' => function($model) {

                                    return "<span style=' text-transform: capitalize'>" . $model->user->first_name . ' ' . $model->user->last_name . '(' . $model->user->email . ')' . "</span>";
                                },
                                'format' => 'html',
                            ],
                            [
                                'attribute' => 'order_id',
                                'header' => 'Asigned By',
                                'value' => function($model) {

                                    return "#" . $model->order_id;
                                },
                                'format' => 'html',
                            ],
                            [
                                'attribute' => 'product_id',
                                'header' => 'Asigned By',
                                'value' => function($model) {

                                    $get_data = \common\models\OrderProducts::find()->where(['order_id' => $model->order_id, 'id' => $model->product_id])->one();

                                    $options = "";
                                    $final_options = "";
                                    if ($get_data != NULL) {
                                        if ($get_data->options != NULL && $get_data->options != "") {
                                            $exp_get_option_datas = explode(',', $get_data->options);
                                            if ($exp_get_option_datas != NULL) {
                                                $get_option_datas = \common\models\AttributesValue::find()->where(['id' => $exp_get_option_datas])->all();
                                                if ($get_option_datas != NULL) {
                                                    foreach ($get_option_datas as $get_option_data) {
                                                        $options .= $get_option_data->attributes0->name . ':' . $get_option_data->value . ",";
                                                    }
                                                }
                                            }
                                        }
                                        if ($options != "") {
                                            $final_options = "(" . $options . ")";
                                        }
                                    }
                                    return $model->orderProduct->product->product_name_en . $final_options;
                                },
                                'format' => 'html',
                            ],
                            [
                                'attribute' => 'created_at',
                                'header' => 'Time',
                                'value' => function($data) {
                                    if ($data->created_at != "") {
                                        return date('d M Y H:i A');
                                    } else {
                                        return "";
                                    }
                                },
                                'format' => 'html',
                            ],
                            [
                                'attribute' => 'status',
                                'header' => 'Ticket Status',
                                'value' => function($data) {
                                    if ($data->status == 1) {
                                        return "<span style='color:violet;font-weight:bold'>Pending</span>";
                                    } else if ($data->status == 2) {

                                        return "<span style='color:blue;font-weight:bold'>Open</span>";
                                    } else if ($data->status == 3) {

                                        return "<span style='color:green;font-weight:bold'>Closed</span>";
                                    }
                                },
                                'format' => 'html',
                            ],
                        ],
                    ])
                    ?>


                </div>

                <?php if ($model->status != 3) { ?>
                    <?php $form = ActiveForm::begin(['action' => 'update-status?id=' . $model->id]); ?>
                    <div class="card">
                        <div class="row m-3">



                            <div class="col-sm-3">
                                <div class="form-group bmd-form-group">
                                    <?= $form->field($model, 'status')->dropDownList(['1' => 'Pending', '2' => 'Open', '3' => 'Closed'], ['prompt' => 'Choose', 'class' => 'form-control']); ?>

                                </div>
                            </div>
                            <div class="col-sm-3">

                                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                            </div>

                        </div>



                        <?php ActiveForm::end(); ?>
                    </div>

                <?php } ?>
            </div>

            <div class="messaging">


<!--            <input type="file"
                   onchange="document.getElementById('result_img').src = window.URL.createObjectURL(this.files[0])">-->
                <div class="inbox_msg">

                    <div class="mesgs">
                        <div class="msg_history">
                            <?php $get_chats = \common\models\SupportChat::find()->where(['ticket_id' => $model->id])->all(); ?>
                            <?php Pjax::begin(['id' => 'chat_section']);
                            ?>
                            <?php
                            if ($get_chats != NULL) {
                                ?>
                                <?php
                                foreach ($get_chats as $get_chat) {
                                    if ($get_chat->sender_type == 2) {
                                        ?>
                                        <div class="outgoing_msg">
                                            <div class="sent_msg">
                                                <?php if ($get_chat->message != "") { ?>
                                                    <p><?= $get_chat->message; ?></p>
                                                <?php } ?>
                                                <?php if ($get_chat->file != NULL) {
                                                    ?>
                                                    <a class="<?= $side; ?>" target="new"  href="<?php echo Yii::$app->request->baseUrl; ?>/../uploads/support-chats/<?php echo $model->id; ?>/<?= $get_chat->file; ?>"> <i class="material-icons">file</i> View File</a>
                                                <?php } ?>
                                                <span class="time_date"> <?php echo date('H:i A', strtotime($get_chat->created_at)) ?> | <?php echo date('Y m d ', strtotime($get_chat->created_at)) ?></span>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="incoming_msg">
                                            <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="<?= $get_chat->ticket->user->first_name; ?>"> </div>
                                            <div class="received_msg">
                                                <div class="received_withd_msg">
                                                    <?php if ($get_chat->message != "") { ?>
                                                        <p><?= $get_chat->message; ?></p>
                                                    <?php } ?>
                                                    <?php if ($get_chat->file != NULL) {
                                                        ?>
                                                        <a class="<?= $side; ?>" download="" target="blank"  href="<?php echo Yii::$app->request->baseUrl; ?>/../uploads/support-chats/<?php echo $model->id; ?>/<?= $get_chat->file; ?>"> <i class="material-icons">file</i> View File</a>
                                                    <?php } ?>
                                                    <span class="time_date"> <?php echo date('H:i A', strtotime($get_chat->created_at)) ?> | <?php echo date('Y m d ', strtotime($get_chat->created_at)) ?></span>
                                                </div>
                                            </div>
                                        </div>

                                    <?php }
                                    ?>

                                <?php } ?>
                            <?php } ?>

                            <!--                            <div class="incoming_msg">
                                                            <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                                                            <div class="received_msg">
                                                                <div class="received_withd_msg">
                                                                    <p>We work directly with our designers and suppliers,
                                                                        and sell direct to you, which means quality, exclusive
                                                                        products, at a price anyone can afford.</p>
                                                                    <span class="time_date"> 11:01 AM    |    Today</span></div>
                                                            </div>
                                                        </div>-->

                            <?php Pjax::end()
                            ?>
                        </div>

                        <div class="chat-input">
                            <?php $formchating = ActiveForm::begin(['action' => 'update-chat?id=' . $model->id, 'id' => 'form_chat', 'options' => ['class' => 'form_chat']]); ?>

                            <!--<input type="text" id="chat-input" placeholder="Send a message...">-->
                            <?= $formchating->field($model, 'file')->fileInput(['class' => 'attachFile', 'id' => 'inputChat', 'onchange' => "document.getElementById('result_img').src = window.URL.createObjectURL(this.files[0])"])->label(FALSE) ?>
                            <?= $formchating->field($model, 'message')->textInput(['id' => 'chat-input', 'placeholder' => 'Send a message...'])->label(FALSE) ?>

                            <div class="submit_item">
                                <button type="button" class="chat-submit buttonFile" id="chat-submit">
                                    <i class="material-icons ">attachment</i>
                                </button>
                                <button type="submit" class="chat-submit" id="chat-submit">
                                    <i class="material-icons">send</i>
                                </button>
                            </div>
                            <img style="float: right" id="result_img" src="<?php echo Yii::$app->request->baseUrl; ?>/img/no-image.jpg" alt="your image" width="50" height="50" />

                            <?php ActiveForm::end(); ?>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>

    </script>
    <?php
// Add Product To cart useing temp session id
    $this->registerJs(<<< EOT_JS_CODE



        $('.buttonFile').click(function () {
            $('.attachFile').trigger('click');
        });

        $(document.body).on('beforeSubmit', '#form_chat', function (e) {
            e.preventDefault();
            try {
                $('.loader-wrapp').show();

                var newformData = new FormData($('#form_chat')[0]);
                var formData = new FormData();
                var message = $('#chat-input').val();

                formData.append('message', message);
//formData.append('action', 'previewImg');
// Attach file
                formData.append('file', $('#inputChat')[0].files[0]);

                var form = $(this);
                // return false if form still have some validation errors
                if (form.find('.has-error').length)
                {
                    return false;
                }
                //  var formData = new FormData($('#form_chat')[0]);
                console.log(formData)
                console.log(form.attr('action'))

                // submit form
                $.ajax({
                    url: "update-chat?id=$model->id",
//                url: form.attr('action'),
                    type: 'post',
                    data: formData,
                    success: function (response)
                    {
                        $.pjax.reload({container: '#chat_section', async: false, timeout: false});
                        $("#form_chat")[0].reset();
                        $('.loader-wrapp').hide();

$('#result_img').attr('src','/caponcms/admin/img/no-image.jpg');
                    },
                    error: function ()
                    {
                        $('.loader-wrapp').hide();

                        console.log('internal server error');
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });

            } catch (err) {
                console.log(err);
                return false;
            }
        }).on('submit', function (e) {
            $('.loader-wrapp').hide();

            e.preventDefault();

        });


EOT_JS_CODE
    );
    ?>