<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\TrOsnovSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tr-osnov-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'tr_osnov_id') ?>

    <?= $form->field($model, 'tr_osnov_kab') ?>

    <?= $form->field($model, 'id_installakt') ?>

    <?= $form->field($model, 'id_mattraffic') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
