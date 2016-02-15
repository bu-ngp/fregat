<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Build */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="build-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'build_name')->textInput(['maxlength' => true, 'class' => 'form-control setsession']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
