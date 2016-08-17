<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\TrMatOsmotr */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tr-mat-osmotr-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_tr_mat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_osmotraktmat')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
