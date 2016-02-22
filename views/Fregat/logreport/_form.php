<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Import\Logreport */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="logreport-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'logreport_date')->textInput() ?>

    <?= $form->field($model, 'logreport_errors')->textInput() ?>

    <?= $form->field($model, 'logreport_updates')->textInput() ?>

    <?= $form->field($model, 'logreport_additions')->textInput() ?>

    <?= $form->field($model, 'logreport_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'logreport_missed')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
