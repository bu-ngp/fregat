<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Material */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="material-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'material_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'material_name1c')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'material_1c')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'material_inv')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'material_serial')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'material_release')->textInput() ?>

    <?= $form->field($model, 'material_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'material_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'material_tip')->textInput() ?>

    <?= $form->field($model, 'material_writeoff')->textInput() ?>

    <?= $form->field($model, 'id_matvid')->textInput() ?>

    <?= $form->field($model, 'id_izmer')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
