<?php

use app\func\Proc;
use app\models\Fregat\Employee;
use app\models\Fregat\Mattraffic;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\datecontrol\DateControl;
use kartik\dynagrid\DynaGrid;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Spismat */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="spismat-form">

    <?php $form = ActiveForm::begin([
        'id' => 'Spismatform',
       // 'enableClientValidation'=>false,
     //   'enableAjaxValidation' => true,
    ]); ?>

    <?=
    $form->field($model, 'spismat_date')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE,
        'options' => [
            'options' => ['placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
        ],
    ])
    ?>

    <?=
    $form->field($model, 'id_mol')->widget(Select2::classname(), array_merge(Proc::DGselect2([
        'model' => $model,
        'resultmodel' => new Employee,
        'fields' => [
            'keyfield' => 'id_mol',
            'resultfield' => 'idperson.auth_user_fullname',
        ],
        'placeholder' => 'Выберете материально-ответственное лицо',
        'fromgridroute' => 'Fregat/employee/index',
        'resultrequest' => 'Fregat/employee/selectinputemloyee',
        'thisroute' => $this->context->module->requestedRoute,
        'methodquery' => 'selectinput',
    ]), [
        'pluginEvents' => [
            "select2:select" => "function() { checkMaterialsCount(); }",
            "select2:unselect" => "function() { spismatCreateDisabled(true); }"
        ],
    ]));
    ?>


    <div class="form-group required"><label class="control-label"
                                   for="period_beg"><?= $model->getAttributeLabel('period') ?></label>
        <div class="row">
            <div class="col-xs-12 col-lg-7">
                <div class="row">
                    <div class="col-sm-6">
                        <?= $form->field($model, 'period_beg', ['enableAjaxValidation' => true])->widget(DateControl::classname(), [
                            'type' => DateControl::FORMAT_DATE,
                            'saveOptions' => ['class' => 'form-control'],
                            'widgetOptions' => [
                                'layout' => '<span class="input-group-addon">ОТ</span>{picker}{remove}{input}',
                                'options' => ['placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
                                'pluginEvents' => [
                                    "clearDate" => "function(e) { clearDatePicker(); }",
                                ],
                            ],
                        ])->label(false);
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'period_end', ['enableAjaxValidation' => true])->widget(DateControl::classname(), [
                            'type' => DateControl::FORMAT_DATE,
                            'saveOptions' => ['class' => 'form-control'],
                            'widgetOptions' => [
                                'layout' => '<span class="input-group-addon">ДО</span>{picker}{remove}{input}',
                                'options' => ['placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
                                'pluginEvents' => [
                                    "clearDate" => "function(e) {  clearDatePicker(); }",
                                ],
                            ],
                        ])->label(false);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= $form->field($model, 'spismat_spisinclude')->checkbox(); ?>

    <div class="alert alert-warning" role="alert" id="spismat_alert" style="display: none;"></div>

    <?php ActiveForm::end(); ?>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::submitButton('<i class="glyphicon glyphicon-plus"></i> Создать', ['class' => 'btn btn-success disabled', 'form' => 'Spismatform', 'disabled' => true, 'id' => 'spismat_create']) ?>
            </div>
        </div>
    </div>

</div>
