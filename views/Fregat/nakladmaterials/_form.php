<?php

use app\func\Proc;
use app\models\Fregat\Mattraffic;
use kartik\select2\Select2;
use kartik\touchspin\TouchSpin;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Nakladmaterials */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="nakladmaterials-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=
    $form->field($model, 'id_mattraffic')->widget(Select2::classname(), array_merge(Proc::DGselect2([
        'model' => $model,
        'resultmodel' => new Mattraffic,
        'fields' => [
            'keyfield' => 'id_mattraffic',
        ],
        'placeholder' => 'Введите инвентарный номер материальной ценности',
        'fromgridroute' => 'Fregat/mattraffic/fornaklad',
        'resultrequest' => 'Fregat/nakladmaterials/selectinputfornakladmaterials',
        'thisroute' => $this->context->module->requestedRoute,
        'methodquery' => 'selectinputfornakladmaterials',
      //  'methodparams' => ['idnaklad' => (string)filter_input(INPUT_GET, 'idnaklad')],
      /*  'dopparams' => [
            'idnaklad' => (string)filter_input(INPUT_GET, 'idnaklad'),
        ],*/
    ]), [
        'pluginEvents' => [
            "select2:select" => "function() { SetMaxNumberByMaterial(); }",
            "select2:unselect" => "function() { UnSetMaxNumberByMaterial(); }"
        ],
    ]));
    ?>

    <?=
    $form->field($model, 'nakladmaterials_number', [
        'inputTemplate' => '<div class="input-group">{input}<span id="mattraffic_number_max" class="input-group-addon"></span></div>'
    ])->widget(TouchSpin::classname(), [
        'options' => ['class' => 'form-control setsession'],
        'pluginOptions' => [
            'verticalbuttons' => true,
            'min' => 0.001,
            'max' => 10000000000,
            'step' => 1,
            'decimals' => 3,
            'forcestepdivisibility' => 'none',
        ]
    ]);
    ?>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Добавить' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
