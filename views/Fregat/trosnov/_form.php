<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\TrOsnov */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tr-osnov-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tr_osnov_kab')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_installakt')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_mattraffic')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
