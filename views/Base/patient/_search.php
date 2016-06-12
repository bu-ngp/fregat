<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Base\PatientSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="patient-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'patient_id') ?>

    <?= $form->field($model, 'patient_fam') ?>

    <?= $form->field($model, 'patient_im') ?>

    <?= $form->field($model, 'patient_ot') ?>

    <?= $form->field($model, 'patient_dr') ?>

    <?php // echo $form->field($model, 'patient_pol') ?>

    <?php // echo $form->field($model, 'id_fias') ?>

    <?php // echo $form->field($model, 'patient_dom') ?>

    <?php // echo $form->field($model, 'patient_korp') ?>

    <?php // echo $form->field($model, 'patient_kvartira') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
