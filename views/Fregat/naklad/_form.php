<?php

use app\func\Proc;
use app\models\Fregat\Employee;
use kartik\datecontrol\DateControl;
use kartik\dynagrid\DynaGrid;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Naklad */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="naklad-form">

    <?php
    $form = ActiveForm::begin([
        'id' => 'Nakladform',
    ]);
    ?>

    <?= !$model->isNewRecord ? $form->field($model, 'naklad_id')->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) : '' ?>

    <?=
    $form->field($model, 'naklad_date')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE,
        'options' => [
            'options' => ['placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
        ],
    ])
    ?>

    <?=
    $form->field($model, 'id_mol_release')->widget(Select2::classname(), Proc::DGselect2([
        'model' => $model,
        'resultmodel' => new Employee,
        'fields' => [
            'keyfield' => 'id_mol_release',
            'resultfield' => 'idperson.auth_user_fullname',
        ],
        'placeholder' => 'Выберете МОЛ, отпустивший материальные ценности',
        'fromgridroute' => 'Fregat/employee/fornaklad',
        'resultrequest' => 'Fregat/employee/selectinputwithmaterials',
        'thisroute' => $this->context->module->requestedRoute,
        'methodquery' => 'selectinputwithmaterials',
        'disabled' => !$model->isNewRecord,
    ]));
    ?>

    <?=
    $form->field($model, 'id_mol_got')->widget(Select2::classname(), Proc::DGselect2([
        'model' => $model,
        'resultmodel' => new Employee,
        'fields' => [
            'keyfield' => 'id_mol_got',
            'resultfield' => 'idperson.auth_user_fullname',
        ],
        'placeholder' => 'Выберете МОЛ, затребовавший материальные ценности',
        'fromgridroute' => 'Fregat/employee/index',
        'resultrequest' => 'Fregat/employee/selectinputemloyee',
        'thisroute' => $this->context->module->requestedRoute,
        'methodquery' => 'selectinput',
    ]));
    ?>

    <?php ActiveForm::end(); ?>

    <?php
    if (!$model->isNewRecord) {
        echo DynaGrid::widget(Proc::DGopts([
            'options' => ['id' => 'nakladmaterialsgrid'],
            'columns' => Proc::DGcols([
                'columns' => [
                    [
                        'attribute' => 'idMattraffic.idMaterial.material_name',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a data-pjax="0" href="' . Url::to(['Fregat/material/update', 'id' => $model->idMattraffic->id_material]) . '">' . $model->idMattraffic->idMaterial->material_name . '</a>';
                        }
                    ],
                    'idMattraffic.idMaterial.material_inv',
                    'idMattraffic.idMaterial.idIzmer.izmer_name',
                    'idMattraffic.idMaterial.idIzmer.izmer_kod_okei',
                    'idMattraffic.idMaterial.material_price',
                    'nakladmaterials_number',
                    'nakladmaterials_sum',
                ],
                'buttons' => [
                    'customupdate' => function ($url, $model) {
                        $customurl = Yii::$app->getUrlManager()->createUrl(['Fregat/nakladmaterials/update', 'id' => $model->primaryKey, 'idnaklad' => (string)filter_input(INPUT_GET, 'id')]);
                        return \yii\helpers\Html::a('<i class="glyphicon glyphicon-pencil"></i>', $customurl, ['title' => 'Обновить', 'class' => 'btn btn-xs btn-warning', 'data-pjax' => '0']);
                    },
                    'deleteajax' => ['Fregat/nakladmaterials/delete'],
                ],
            ]),
            'gridOptions' => [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-paperclip"></i> Затребованные материальные ценности</h3>',
                    'before' => Html::a('<i class="glyphicon glyphicon-download"></i> Добавить материальную ценность', ['Fregat/nakladmaterials/create',
                        'idnaklad' => $model->primaryKey,
                    ], ['class' => 'btn btn-success', 'data-pjax' => '0']),
                ],
            ]
        ]));
    }
    ?>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Nakladform']) ?>
                <?php
                if (!$model->isNewRecord && $model->nakladmaterials)
                    echo Html::button('<i class="glyphicon glyphicon-list"></i> Скачать требование-накладную', ['id' => 'DownloadReport', 'class' => 'btn btn-info', 'onclick' => 'DownloadReport("' . Url::to(['Fregat/naklad/naklad-report']) . '", $(this)[0].id, {id: ' . $model->primaryKey . '} )']);
                ?>
            </div>
        </div>
    </div>
</div>
