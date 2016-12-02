<?php

use app\func\Proc;
use app\models\Fregat\Employee;
use app\models\Fregat\Mattraffic;
use app\models\Fregat\Schetuchet;
use kartik\datecontrol\DateControl;
use kartik\dynagrid\DynaGrid;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Spisosnovakt */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="spisosnovakt-form">

    <?php $form = ActiveForm::begin([
        'id' => 'Spisosnovaktform',
    ]); ?>


    <?= !$model->isNewRecord ? $form->field($model, 'spisosnovakt_id')->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) : '' ?>

    <?=
    $form->field($model, 'spisosnovakt_date')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE,
        'options' => [
            'options' => ['placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
        ],
    ])
    ?>

    <?=
    $form->field($model, 'id_schetuchet')->widget(Select2::classname(), Proc::DGselect2([
        'model' => $model,
        'resultmodel' => new Schetuchet,
        'fields' => [
            'keyfield' => 'id_schetuchet',
            'resultfield' => 'schetuchet_kod',
            'showresultfields' => ['schetuchet_kod', 'schetuchet_name'],
        ],
        'placeholder' => 'Выберете счет учета',
        'resultrequest' => 'Fregat/schetuchet/selectinput',
        'thisroute' => $this->context->module->requestedRoute,
        'fromgridroute' => 'Fregat/schetuchet/index',
        'methodquery' => 'selectinput',
        'disabled' => !$model->isNewRecord,
        'onlyAjax' => false,
    ]));
    ?>

    <?=
    $form->field($model, 'id_mol')->widget(Select2::classname(), Proc::DGselect2([
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
        'disabled' => !$model->isNewRecord,
    ]));
    ?>

    <?=
    $form->field($model, 'id_employee')->widget(Select2::classname(), Proc::DGselect2([
        'model' => $model,
        'resultmodel' => new Employee,
        'fields' => [
            'keyfield' => 'id_employee',
            'resultfield' => 'idperson.auth_user_fullname',
        ],
        'placeholder' => 'Выберете иное лицо',
        'fromgridroute' => 'Fregat/employee/index',
        'resultrequest' => 'Fregat/employee/selectinputemloyee',
        'thisroute' => $this->context->module->requestedRoute,
        'methodquery' => 'selectinput',
    ]));
    ?>

    <?php ActiveForm::end(); ?>

    <?php
    if (!$model->isNewRecord) {
        echo $form->field(new Mattraffic, 'mattraffic_id')->widget(Select2::classname(), [
            'options' => ['placeholder' => 'Введите инвентарный номер материальной ценности', 'class' => 'form-control'],
            'theme' => Select2::THEME_BOOTSTRAP,
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => Url::to(['Fregat/mattraffic/selectinputforspisosnovakt-fast']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term, spisosnovakt_id: ' . $_GET['id'] . '} }'),
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            ],
            'addon' => [
                'append' => [
                    'content' => Html::button('<i class="glyphicon glyphicon-arrow-down"></i>  Вставить в таблицу', ['class' => 'btn btn-success', 'id' => 'addspisosnovmaterials', 'onclick' => 'AddMattraffic(' . $_GET['id'] . ')']),
                    'asButton' => true
                ]
            ],
        ])->label('Для быстрого добавления материальных ценностей ( при условии что количество на списание = 1 )');
    }
    ?>

    <?php
    if (!$model->isNewRecord) {
        echo DynaGrid::widget(Proc::DGopts([
            'options' => ['id' => 'spisosnovmaterialsgrid'],
            'columns' => Proc::DGcols([
                'columns' => [
                    'idMattraffic.idMaterial.material_name',
                    'idMattraffic.idMaterial.material_inv',
                    'idMattraffic.idMaterial.material_serial',
                    'idMattraffic.idMaterial.material_release',
                    'spisosnovmaterials_number',
                    'idMattraffic.idMaterial.material_price',
                ],
                'buttons' => [
                    'update' => ['Fregat/spisosnovmaterials/update'],
                    'deleteajax' => ['Fregat/spisosnovmaterials/delete'],
                ],
            ]),
            'gridOptions' => [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-compressed"></i> Списываемые материальные ценности</h3>',
                    'before' => Html::a('<i class="glyphicon glyphicon-download"></i> Добавить материальную ценность', ['Fregat/spisosnovmaterials/create',
                        'idspisosnovakt' => $model->primaryKey,
                    ], ['class' => 'btn btn-success', 'data-pjax' => '0']),
                ],
            ]
        ]));
    }
    ?>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Spisosnovaktform']) ?>
                <?php
                if (!$model->isNewRecord)
                    echo Html::button('<i class="glyphicon glyphicon-list"></i> Скачать акт', ['id' => 'DownloadReport', 'class' => 'btn btn-info', 'onclick' => 'DownloadReport("' . Url::to(['Fregat/spisosnovakt/spisosnovakt-report']) . '", $(this)[0].id, {id: ' . $model->primaryKey . '} )']);
                ?>
            </div>
        </div>
    </div>

</div>
