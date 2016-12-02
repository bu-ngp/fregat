<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Fregat\Dolzh;
use app\models\Fregat\Podraz;
use app\models\Fregat\Build;
use kartik\select2\Select2;
use app\func\Proc;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="employee-form">

<?php $form = ActiveForm::begin(); ?>

<?=
$form->field($model, 'id_dolzh')->widget(Select2::classname(), array_merge(Proc::DGselect2(array_merge([
    'model' => $model,
    'resultmodel' => new Dolzh,
    'fields' => [
        'keyfield' => 'id_dolzh',
        'resultfield' => 'dolzh_name',
    ],
    'placeholder' => 'Выберете должность',
    'resultrequest' => 'Fregat/dolzh/selectinput',
    'thisroute' => $this->context->module->requestedRoute,
    'dopparams' => [
        'iduser' => $iduser,
    ],
    'disabled' => $OnlyBuildEdit,
    'onlyAjax' => false,
], $OnlyBuildEdit ? [] : ['fromgridroute' => 'Fregat/dolzh/index',]))));
?>

<?=
$form->field($model, 'id_podraz')->widget(Select2::classname(), array_merge(Proc::DGselect2(array_merge([
    'model' => $model,
    'resultmodel' => new Podraz,
    'fields' => [
        'keyfield' => 'id_podraz',
        'resultfield' => 'podraz_name',
    ],
    'placeholder' => 'Выберете подразделение',
    'resultrequest' => 'Fregat/podraz/selectinput',
    'thisroute' => $this->context->module->requestedRoute,
    'dopparams' => [
        'iduser' => $iduser,
    ],
    'disabled' => $OnlyBuildEdit,
    'onlyAjax' => false,
], $OnlyBuildEdit ? [] : ['fromgridroute' => 'Fregat/podraz/index',]))));
?>

<?=
$form->field($model, 'id_build')->widget(Select2::classname(), Proc::DGselect2([
    'model' => $model,
    'resultmodel' => new Build,
    'fields' => [
        'keyfield' => 'id_build',
        'resultfield' => 'build_name',
    ],
    'placeholder' => 'Выберете здание',
    'fromgridroute' => 'Fregat/build/index',
    'resultrequest' => 'Fregat/build/selectinput',
    'thisroute' => $this->context->module->requestedRoute,
    'dopparams' => [
        'iduser' => $iduser,
    ],
    'onlyAjax' => false,
]));
?>

<?=
$form->field($model, 'employee_dateinactive')->widget(DateControl::classname(), [
    'type' => DateControl::FORMAT_DATE,
    'options' => [
        'options' => ['placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession', 'disabled' => $OnlyBuildEdit],
    ],
])
?>

<?=
$form->field($model, 'employee_importdo')->checkbox(['disabled' => $OnlyBuildEdit]);
?>

    <div class="form-group">
        <div class="form-group">
            <div class="panel panel-default">
                <div class="panel-heading">

                    <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>

<?php ActiveForm::end(); ?>