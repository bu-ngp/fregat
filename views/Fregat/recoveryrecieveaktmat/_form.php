<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Recoveryrecieveaktmat */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recoveryrecieveaktmat-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'recoveryrecieveaktmat_result')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'recoveryrecieveaktmat_repaired')->textInput() ?>

    <?= $form->field($model, 'recoveryrecieveaktmat_date')->textInput() ?>

    <?= $form->field($model, 'id_recoverysendakt')->textInput() ?>

    <?= $form->field($model, 'id_tr_mat_osmotr')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
