<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use app\func\Proc;
use kartik\touchspin\TouchSpin;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\TrMatOsmotr */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tr-mat-osmotr-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=
    $form->field($model, 'id_tr_mat')->widget(Select2::classname(), Proc::DGselect2([
        'model' => $model,
        'resultmodel' => new app\models\Fregat\TrMat,
        'fields' => [
            'keyfield' => 'id_tr_mat',
        ],
        'placeholder' => 'Введите инвентарный номер или наименование материала',
        'fromgridroute' => 'Fregat/tr-mat/fortrmatosmotr',
        'resultrequest' => 'Fregat/tr-mat/selectinputfortrmatosmotr',
        'thisroute' => $this->context->module->requestedRoute,
        'methodquery' => 'selectinputfortrmatosmotr',
        'methodparams' => ['idosmotraktmat' => (string)filter_input(INPUT_GET, 'id')],
        'dopparams' => [
            'idosmotraktmat' => (string)filter_input(INPUT_GET, 'id'),
        ],
    ]));
    ?>

    <?=
    $form->field($model, 'tr_mat_osmotr_number')->widget(TouchSpin::classname(), [
        'options' => ['class' => 'form-control setsession'],
        'pluginOptions' => [
            'verticalbuttons' => true,
            'min' => 0,
            'max' => 10000000000,
            'step' => 1,
            'decimals' => 3,
            'forcestepdivisibility' => 'none',
        ]
    ]);
    ?>

    <?=
    $form->field($model, 'id_reason')->widget(Select2::classname(), Proc::DGselect2([
        'model' => $model,
        'resultmodel' => new app\models\Fregat\Reason,
        'fields' => [
            'keyfield' => 'id_reason',
            'resultfield' => 'reason_text',
        ],
        'placeholder' => 'Выберете причину неисправности',
        'fromgridroute' => 'Fregat/reason/index',
        'resultrequest' => 'Fregat/reason/selectinput',
        'thisroute' => $this->context->module->requestedRoute,
        'onlyAjax' => false,
    ]));
    ?>

    <?=
    $form->field($model, 'tr_mat_osmotr_comment')->textarea([
        'class' => 'form-control setsession',
        'maxlength' => 1024,
        'placeholder' => 'Введите дополнительную информацию о неисправности',
        'rows' => 10,
        'style' => 'resize: none',
    ]);
    ?>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">

                <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
