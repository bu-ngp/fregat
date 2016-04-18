<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\OsmotraktSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="osmotrakt-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'osmotrakt_id') ?>

    <?= $form->field($model, 'osmotrakt_comment') ?>

    <?= $form->field($model, 'id_reason') ?>

    <?= $form->field($model, 'id_user') ?>

    <?= $form->field($model, 'id_master') ?>

    <?php // echo $form->field($model, 'id_mattraffic') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
