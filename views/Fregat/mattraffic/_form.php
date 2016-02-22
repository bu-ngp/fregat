<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Mattraffic */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mattraffic-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'mattraffic_date')->textInput() ?>

    <?= $form->field($model, 'mattraffic_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_material')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_mol')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
