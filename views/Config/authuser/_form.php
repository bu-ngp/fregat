<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\web\Session;

/* @var $this yii\web\View */
/* @var $model app\models\Config\Authuser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="authuser-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'Authassignmentform',
    ]);
    ?>

    <?php
    echo $form->field($model, 'auth_user_fullname')->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => $model->scenario === 'Changepassword']);
    echo $form->field($model, 'auth_user_login')->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => $model->scenario === 'Changepassword']);


    if ($model->isNewRecord || $model->scenario === 'Changepassword') {
        echo $form->field($model, 'auth_user_password')->passwordInput(['maxlength' => true, 'autocomplete' => 'off']);
        echo $form->field($model, 'auth_user_password2')->passwordInput(['maxlength' => true, 'autocomplete' => 'off']);
    }
    ?>

    <?php ActiveForm::end(); ?>

    <?php
    if (!$model->isNewRecord && $model->scenario !== 'Changepassword') {
        $session = new Session;
        $session->open();

        echo DynaGrid::widget(Proc::DGopts([
                    'columns' => Proc::DGcols([
                        'columns' => [
                            'itemname.description',
                        ],
                        'buttons' => [
                            'deletecustom' => function ($url, $model) {
                                $customurl = Yii::$app->getUrlManager()->createUrl(['Config/authassignment/delete', 'item_name' => $model->item_name, 'user_id' => $model->user_id]);
                                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-trash"></i>', $customurl, ['title' => 'Удалить'/* , 'data-pjax' => '0' */, 'class' => 'btn btn-xs btn-danger', 'data' => [
                                                'confirm' => "Вы уверены, что хотите удалить запись?",
                                                'method' => 'post',
                                ]]);
                            }
                                ],
                            ]),
                            'gridOptions' => [
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'options' => ['id' => 'authassignmentgrid'],
                                'panel' => [
                                    'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-paperclip"></i> Привязать к роли</h3>',
                                    'before' => Html::a('<i class="glyphicon glyphicon-download"></i> Добавить роль', ['Config/authitem/forauthassignment',
                                        'foreignmodel' => 'Authassignment', //substr($model->className(), strrpos($model->className(), '\\') + 1),
                                        'url' => $this->context->module->requestedRoute,
                                        'field' => 'item_name',
                                        'id' => $model->primaryKey,
                                            // 'id' => $_GET['id'],
                                            ], ['class' => 'btn btn-success', 'data-pjax' => '0']),
                                ],
                            ]
                ]));

                $session->close();
            }
            ?>

            <div class="form-group">

                <?php
                $label = '<i class="glyphicon glyphicon-plus"></i> Создать';
                $class = 'btn btn-success';

                if (!$model->isNewRecord) {
                    if ($model->scenario === 'Changepassword') {
                        $label = '<i class="glyphicon glyphicon-lock"></i> Сменить пароль';
                        $class = 'btn btn-info';
                    } else {
                        $label = '<i class="glyphicon glyphicon-edit"></i> Обновить';
                        $class = 'btn btn-primary';
                    }
                }
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?= Html::submitButton($label, ['class' => $class, 'form' => 'Authassignmentform']) ?>
                        <?php if (!$model->isNewRecord && $model->scenario !== 'Changepassword'): ?>
                            <?= Html::a('<i class="glyphicon glyphicon-lock"></i> Сменить пароль', ['Config/authuser/changepassword', 'id' => $model['auth_user_id']], ['class' => 'btn btn-info']) ?>
                        <?php endif; ?>
            </div>
        </div>


    </div>

</div>
