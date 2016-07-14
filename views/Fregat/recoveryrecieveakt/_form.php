<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Recoveryrecieveakt */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recoveryrecieveakt-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'Recoverysendaktform',
    ]);
    ?>

    <?= $form->field($model, 'id_osmotrakt')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_recoverysendakt')->textInput() ?>

    <?= $form->field($model, 'recoveryrecieveakt_result')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'recoveryrecieveakt_repaired')->textInput() ?>

    <?= $form->field($model, 'recoveryrecieveakt_date')->textInput() ?>

    <?php ActiveForm::end(); ?>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Назад', Proc::GetPreviousURLBreadcrumbsFromSession(), ['class' => 'btn btn-info']) ?>
                <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Recoverysendaktform']) ?>
            </div>
        </div> 
    </div>
</div>
