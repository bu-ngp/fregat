<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\MattrafficSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mattraffic-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'mattraffic_id') ?>

    <?= $form->field($model, 'mattraffic_date') ?>

    <?= $form->field($model, 'mattraffic_number') ?>

    <?= $form->field($model, 'id_material') ?>

    <?= $form->field($model, 'id_mol') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
