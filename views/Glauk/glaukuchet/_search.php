<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Glauk\GlaukuchetSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="glaukuchet-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'glaukuchet_id') ?>

    <?= $form->field($model, 'glaukuchet_uchetbegin') ?>

    <?= $form->field($model, 'glaukuchet_detect') ?>

    <?= $form->field($model, 'glaukuchet_deregdate') ?>

    <?= $form->field($model, 'glaukuchet_deregreason') ?>

    <?php // echo $form->field($model, 'glaukuchet_stage') ?>

    <?php // echo $form->field($model, 'glaukuchet_operdate') ?>

    <?php // echo $form->field($model, 'glaukuchet_rlocat') ?>

    <?php // echo $form->field($model, 'glaukuchet_invalid') ?>

    <?php // echo $form->field($model, 'glaukuchet_lastvisit') ?>

    <?php // echo $form->field($model, 'glaukuchet_lastmetabol') ?>

    <?php // echo $form->field($model, 'id_patient') ?>

    <?php // echo $form->field($model, 'id_employee') ?>

    <?php // echo $form->field($model, 'id_class_mkb') ?>

    <?php // echo $form->field($model, 'glaukuchet_comment') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
