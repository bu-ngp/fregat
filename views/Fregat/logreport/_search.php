<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Import\LogreportSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="logreport-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'logreport_id') ?>

    <?= $form->field($model, 'logreport_date') ?>

    <?= $form->field($model, 'logreport_errors') ?>

    <?= $form->field($model, 'logreport_updates') ?>

    <?= $form->field($model, 'logreport_additions') ?>

    <?php // echo $form->field($model, 'logreport_amount') ?>

    <?php // echo $form->field($model, 'logreport_missed') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
