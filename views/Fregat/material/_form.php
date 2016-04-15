<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Fregat\Matvid;
use app\models\Fregat\Izmer;
use kartik\select2\Select2;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\web\Session;
use kartik\datetime\DateTimePicker;
use app\models\Fregat\Mattraffic;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Material */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="material-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'Materialform',
    ]);
    ?>

    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Материальная ценность') ?></div>
        <div class="panel-body">

            <?=
            $form->field($model, 'material_tip')->widget(Select2::classname(), [
                'hideSearch' => true,
                'data' => [1 => 'Основное средство', 2 => 'Материал'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'options' => ['placeholder' => 'Выберете тип материальной ценности', 'class' => 'form-control setsession'],
                'theme' => Select2::THEME_BOOTSTRAP,
            ]);
            ?>

            <?=
            $form->field($model, 'id_matvid')->widget(Select2::classname(), Proc::DGselect2([
                        'model' => $model,
                        'resultmodel' => new Matvid,
                        'fields' => [
                            'keyfield' => 'id_matvid',
                            'resultfield' => 'matvid_name',
                        ],
                        'placeholder' => 'Выберете вид материальной ценности',
                        'fromgridroute' => 'Fregat/matvid/index',
                        'resultrequest' => 'Fregat/matvid/selectinput',
                        'thisroute' => $this->context->module->requestedRoute,
            ]));
            ?>

            <?= $form->field($model, 'material_name')->textInput(['maxlength' => true, 'class' => 'form-control setsession']) ?>

            <?= $form->field($model, 'material_inv')->textInput(['maxlength' => true, 'class' => 'form-control setsession']) ?>

            <?=
            $form->field($model, 'material_number')->widget(kartik\touchspin\TouchSpin::classname(), [
                'options' => ['class' => 'form-control setsession'],
                'pluginOptions' => [
                    'verticalbuttons' => true,
                    'min' => 1,
                    'max' => 10000000000,
                    'step' => 1,
                    'decimals' => 3,
                    'forcestepdivisibility' => 'none',
                ]
            ]);
            ?>

            <?=
            $form->field($model, 'id_izmer')->widget(Select2::classname(), Proc::DGselect2([
                        'model' => $model,
                        'resultmodel' => new Izmer,
                        'fields' => [
                            'keyfield' => 'id_izmer',
                            'resultfield' => 'izmer_name',
                        ],
                        'placeholder' => 'Выберете единицу измерения',
                        'fromgridroute' => 'Fregat/izmer/index',
                        'resultrequest' => 'Fregat/izmer/selectinput',
                        'thisroute' => $this->context->module->requestedRoute,
            ]));
            ?>

            <?=
            $form->field($model, 'material_price')->widget(kartik\touchspin\TouchSpin::classname(), [
                'options' => ['class' => 'form-control setsession'],
                'pluginOptions' => [
                    'verticalbuttons' => true,
                    'min' => 0,
                    'max' => 1000000000,
                    'step' => 1,
                    'decimals' => 2,
                    'forcestepdivisibility' => 'none',
                ]
            ]);
            ?>

            <?= $form->field($model, 'material_serial')->textInput(['maxlength' => true, 'class' => 'form-control setsession']) ?>

            <?=
            $form->field($model, 'material_release')->widget(DateTimePicker::classname(), [
                'options' => ['placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
                'pluginOptions' => [
                    'format' => 'dd.mm.yyyy',
                    'minView' => 2,
                    'maxView' => 3,
                    'autoclose' => true,
                ]
            ])
            ?>

            <?=
            $form->field($model, 'material_importdo')->checkbox();
            ?>
        </div>
    </div>
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Материально-ответственное лицо') ?></div>
        <div class="panel-body">  

            <?=
            $form->field($Mattraffic, 'id_mol')->widget(Select2::classname(), Proc::DGselect2([
                        'model' => $Mattraffic,
                        'resultmodel' => new app\models\Fregat\Employee,
                        'fields' => [
                            'keyfield' => 'id_mol',
                            'resultfield' => 'idperson.auth_user_fullname',
                        ],
                        'placeholder' => 'Выберете материально отчетственное лицо',
                        'fromgridroute' => 'Fregat/employee/index',
                        'resultrequest' => 'Fregat/employee/selectinputformaterial',
                        'thisroute' => $this->context->module->requestedRoute,
                        'methodquery' => 'selectinput',
            ]));
            ?>
            
            <?=
            $form->field($Mattraffic, 'mattraffic_date')->widget(DateTimePicker::classname(), [
                'options' => ['placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
                'pluginOptions' => [
                    'format' => 'dd.mm.yyyy',
                    'minView' => 2,
                    'maxView' => 3,
                    'autoclose' => true,
                ]
            ])
            ?>

        </div>
    </div>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Materialform']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
