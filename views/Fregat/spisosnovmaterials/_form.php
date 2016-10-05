<?php

use app\func\Proc;
use app\models\Fregat\Mattraffic;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Spisosnovmaterials */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="spisosnovmaterials-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=
    $form->field($model, 'id_mattraffic', ['enableClientValidation' => false])->widget(Select2::classname(), Proc::DGselect2([
        'model' => $model,
        'resultmodel' => new Mattraffic,
        'fields' => [
            'keyfield' => 'id_mattraffic',
        ],
        'placeholder' => 'Введите инвентарный номер материальной ценности',
        'fromgridroute' => 'Fregat/mattraffic/forspisosnovakt',
        'resultrequest' => 'Fregat/osmotrakt/selectinputforspisosnovakt',
        'thisroute' => $this->context->module->requestedRoute,
        'methodquery' => 'selectinputforspisosnovakt',
    ]));
    ?>

    <?php
    echo $form->field($model, 'spisosnovmaterials_number', ['enableClientValidation' => false])->widget(kartik\touchspin\TouchSpin::classname(), [
        'options' => ['class' => 'form-control setsession'],
        'pluginOptions' => [
            'verticalbuttons' => true,
            'min' => 0.001,
            'max' => 10000000000,
            'step' => 1,

            'decimals' => 3,
            'forcestepdivisibility' => 'none',
        ],
    ])->label('Количество для списания');
    ?>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Osmotraktform']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
