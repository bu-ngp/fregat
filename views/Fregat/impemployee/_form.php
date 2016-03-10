<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Impemployee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="impemployee-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_importemployee')->textInput() ?>

    <?= $form->field($model, 'id_employee')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>