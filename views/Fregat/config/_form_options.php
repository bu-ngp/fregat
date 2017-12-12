<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use app\models\Fregat\Employee;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Fregatsettings */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fregatsettings-form">

    <?php
    $form = ActiveForm::begin();
    ?>
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div
                class="panel-heading"><?= Html::encode('Настройка отправки электронного письма при отправке в организацию (из справочника организаций)') ?></div>
        <div class="panel-body">
            <?= $form->field($model, 'fregatsettings_recoverysend_emailtheme')->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'autofocus' => true]) ?>

            <?= $form->field($model, 'fregatsettings_recoverysend_emailfrom')->textInput(['maxlength' => true, 'class' => 'form-control setsession']) ?>
        </div>
    </div>
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Настройка справочников') ?></div>
        <div class="panel-body">
            <?= $form->field($model, 'fregatsettings_employee_inactive_hidden')->checkbox() ?>
        </div>
    </div>
    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::submitButton('<i class="glyphicon glyphicon-edit"></i> Сохранить', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
