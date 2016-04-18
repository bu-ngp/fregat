<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Osmotrakt */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="osmotrakt-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'osmotrakt_comment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_reason')->textInput() ?>

    <?= $form->field($model, 'id_user')->textInput() ?>

    <?= $form->field($model, 'id_master')->textInput() ?>

    <?= $form->field($model, 'id_mattraffic')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
