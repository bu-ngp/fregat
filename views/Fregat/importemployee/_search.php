<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\ImportemployeeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="importemployee-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'importemployee_id') ?>

    <?= $form->field($model, 'importemployee_combination') ?>

    <?= $form->field($model, 'id_build') ?>

    <?= $form->field($model, 'id_podraz') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
