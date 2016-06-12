<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Glauk\Glaukuchet */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="glaukuchet-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'glaukuchet_uchetbegin')->textInput() ?>

    <?= $form->field($model, 'glaukuchet_detect')->textInput() ?>

    <?= $form->field($model, 'glaukuchet_deregdate')->textInput() ?>

    <?= $form->field($model, 'glaukuchet_deregreason')->textInput() ?>

    <?= $form->field($model, 'glaukuchet_stage')->textInput() ?>

    <?= $form->field($model, 'glaukuchet_operdate')->textInput() ?>

    <?= $form->field($model, 'glaukuchet_rlocat')->textInput() ?>

    <?= $form->field($model, 'glaukuchet_invalid')->textInput() ?>

    <?= $form->field($model, 'glaukuchet_lastvisit')->textInput() ?>

    <?= $form->field($model, 'glaukuchet_lastmetabol')->textInput() ?>

    <?= $form->field($model, 'id_patient')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_employee')->textInput() ?>

    <?= $form->field($model, 'id_class_mkb')->textInput() ?>

    <?= $form->field($model, 'glaukuchet_comment')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
