<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Osmotraktmat */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="osmotraktmat-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'osmotraktmat_comment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'osmotraktmat_date')->textInput() ?>

    <?= $form->field($model, 'id_reason')->textInput() ?>

    <?= $form->field($model, 'id_tr_mat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_master')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
