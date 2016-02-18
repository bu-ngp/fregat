<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Config\AuthuserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="authuser-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'auth_user_id') ?>

    <?= $form->field($model, 'auth_user_fullname') ?>

    <?= $form->field($model, 'auth_user_login') ?>

    <?= $form->field($model, 'auth_user_password') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
