<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Spismatmaterials */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="spismatmaterials-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_spismat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_mattraffic')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
