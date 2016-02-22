<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\MaterialSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="material-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'material_id') ?>

    <?= $form->field($model, 'material_name') ?>

    <?= $form->field($model, 'material_name1c') ?>

    <?= $form->field($model, 'material_1c') ?>

    <?= $form->field($model, 'material_inv') ?>

    <?php // echo $form->field($model, 'material_serial') ?>

    <?php // echo $form->field($model, 'material_release') ?>

    <?php // echo $form->field($model, 'material_number') ?>

    <?php // echo $form->field($model, 'material_price') ?>

    <?php // echo $form->field($model, 'material_tip') ?>

    <?php // echo $form->field($model, 'material_writeoff') ?>

    <?php // echo $form->field($model, 'id_matvid') ?>

    <?php // echo $form->field($model, 'id_izmer') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
