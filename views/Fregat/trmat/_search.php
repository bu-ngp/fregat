<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\TrMatSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tr-mat-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'tr_mat_id') ?>

    <?= $form->field($model, 'id_installakt') ?>

    <?= $form->field($model, 'id_mattraffic') ?>

    <?= $form->field($model, 'id_parent') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
