<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Grupavid */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grupavid-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'grupavid_main')->textInput() ?>

    <?= $form->field($model, 'id_grupa')->textInput() ?>

    <?= $form->field($model, 'id_matvid')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
