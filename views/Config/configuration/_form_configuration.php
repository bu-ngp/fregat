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

    <?= $form->field($model, 'fregatsettings_glavvrach_name')->textInput(['maxlength' => true, 'class' => 'form-control setsession inputuppercase', 'autofocus' => true]) ?>

    <?= $form->field($model, 'fregatsettings_uchrezh_name')->textInput(['maxlength' => true, 'class' => 'form-control setsession inputuppercase']) ?>

    <?= $form->field($model, 'fregatsettings_uchrezh_namesokr')->textInput(['maxlength' => true, 'class' => 'form-control setsession inputuppercase']) ?>

    <?= $form->field($model, 'fregatsettings_glavbuh_name')->textInput(['maxlength' => true, 'class' => 'form-control setsession inputuppercase', 'autofocus' => true]) ?>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::submitButton('<i class="glyphicon glyphicon-edit"></i> Сохранить', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
