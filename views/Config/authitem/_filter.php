<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

$fmodel = substr($model->className(), strrpos($model->className(), '\\') + 1);
?>

<div class="authitemfilter-form">
    <?php $form = ActiveForm::begin(['options' => ['id' => $fmodel . '-form', 'data-pjax' => true]]); ?>
    <div class="insideforms">
        <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
            <div class="panel-heading"><?= Html::encode('Основные') ?></div>
            <div class="panel-body">
                <?= $form->field($model, 'onlyrootauthitems_mark')->checkbox(/* ['uncheck' => null] */); ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::Button('<i class="glyphicon glyphicon-ok"></i> Применить', ['class' => 'btn btn-primary', 'id' => $fmodel . '_apply']) ?>
                <?= Html::Button('<i class="glyphicon glyphicon-remove"></i> Отмена', ['class' => 'btn btn-danger', 'id' => $fmodel . '_close']) ?>
            </div>
        </div> 
    </div>

    <?php ActiveForm::end(); ?>
</div>
