<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use app\models\Fregat\Employee;
use \yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Osmotraktmat */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="osmotraktmat-form">

    <?php
    $form = ActiveForm::begin([
        'id' => 'Osmotraktmatform',
    ]);
    ?>

    <?= !$model->isNewRecord ? $form->field($model, 'osmotraktmat_id')->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) : '' ?>

    <?=
    $form->field($model, 'osmotraktmat_date')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE,
        'options' => [
            'options' => ['placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
        ],
    ])
    ?>

    <?=
    $form->field($model, 'id_master')->widget(Select2::classname(), Proc::DGselect2([
        'model' => $model,
        'resultmodel' => new Employee,
        'fields' => [
            'keyfield' => 'id_master',
            'resultfield' => 'idperson.auth_user_fullname',
        ],
        'placeholder' => 'Выберете пользователя',
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
            'options' => ['id' => 'tr-mat-osmotrgrid'],
            'columns' => Proc::DGcols([
                'columns' => [
                    'idTrMat.idMattraffic.idMaterial.idMatv.matvid_name',
                    'idTrMat.idMattraffic.idMaterial.material_name',
                    'idTrMat.idMattraffic.idMaterial.material_inv',
                    'idTrMat.idMattraffic.mattraffic_number',
                    [
                        'attribute' => 'idTrMat.idMattraffic.idMol.idperson.auth_user_fullname',
                        'label' => 'ФИО материально-ответственного лица',
                    ],
                    [
                        'attribute' => 'idTrMat.idMattraffic.idMol.iddolzh.dolzh_name',
                        'label' => 'Должность материально-ответственного лица',
                    ],
                    [
                        'attribute' => 'idTrMat.idParent.material_name',
                        'label' => 'В составе материальной ценности',
                    ],
                    [
                        'attribute' => 'idTrMat.idParent.material_inv',
                        'label' => 'Инвентарный номер материальной ценности в которую укомплектовано',
                    ],
                    'idReason.reason_text',
                    'tr_mat_osmotr_comment'
                ],
                'buttons' => [
                    'update' => ['Fregat/tr-mat-osmotr/update'],
                    'deleteajax' => ['Fregat/tr-mat-osmotr/delete'],
                ],
            ]),
            'gridOptions' => [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-compressed"></i> Осмотренные материалы</h3>',
                    'before' => Html::a('<i class="glyphicon glyphicon-download"></i> Добавить материал', ['Fregat/tr-mat-osmotr/create',
                        'foreignmodel' => 'TrMatOsmotr',
                        'url' => $this->context->module->requestedRoute,
                        'field' => 'id_osmotraktmat',
                        'id' => $model->primaryKey,
                    ], ['class' => 'btn btn-success', 'data-pjax' => '0']),
                ],
            ]
        ]));
    }
    ?>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Назад', Proc::GetPreviousURLBreadcrumbsFromSession(), ['class' => 'btn btn-info']) ?>
                <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Osmotraktmatform']) ?>
                <?php
                if (!$model->isNewRecord)
                    echo Html::button('<i class="glyphicon glyphicon-list"></i> Скачать акт', ['id' => 'DownloadReport', 'class' => 'btn btn-info', 'onclick' => 'DownloadReport("' . Url::to(['Fregat/osmotraktmat/osmotraktmat-report']) . '", $(this)[0].id, {id: ' . $model->primaryKey . '} )']);
                ?>
            </div>
        </div>
    </div>

</div>
