<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\GrupavidSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grupavid-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'grupavid_id') ?>

    <?= $form->field($model, 'grupavid_main') ?>

    <?= $form->field($model, 'id_grupa') ?>

    <?= $form->field($model, 'id_matvid') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
