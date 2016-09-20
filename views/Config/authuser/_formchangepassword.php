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
    echo $form->field($model, 'auth_user_fullname')->textInput(array_merge(['maxlength' => true, 'class' => 'form-control setsession inputuppercase', 'disabled' => $model->scenario === 'Changepassword' || $EmployeeSpecEdit], ($model->scenario === 'Changepassword' || $EmployeeSpecEdit) ? [] : ['autofocus' => true]));
    echo $form->field($model, 'auth_user_login')->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => $model->scenario === 'Changepassword' || $EmployeeSpecEdit]);


    if ($model->isNewRecord || $model->scenario === 'Changepassword') {
        echo $form->field($model, 'auth_user_password')->passwordInput(array_merge(['maxlength' => true, 'autocomplete' => 'off'], $model->scenario === 'Changepassword' ? ['autofocus' => true] : []))->label('Новый пароль');
        echo $form->field($model, 'auth_user_password2')->passwordInput(['maxlength' => true, 'autocomplete' => 'off']);
    }
    ?>

    <?php ActiveForm::end(); ?>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                    <?= Html::submitButton('<i class="glyphicon glyphicon-lock"></i> Сменить пароль', ['class' => 'btn btn-info', 'form' => 'Authassignmentform']) ?>
            </div>
        </div>
    </div>

</div>
