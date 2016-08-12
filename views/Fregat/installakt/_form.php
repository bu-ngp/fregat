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
/* @var $model app\models\Fregat\Installakt */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="installakt-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'Installaktform',
    ]);
    ?>

    <?= !$model->isNewRecord ? $form->field($model, 'installakt_id')->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) : '' ?>

    <?=
    $form->field($model, 'id_installer')->widget(Select2::classname(), Proc::DGselect2([
                'model' => $model,
                'resultmodel' => new Employee,
                'fields' => [
                    'keyfield' => 'id_installer',
                    'resultfield' => 'idperson.auth_user_fullname',
                ],
                'placeholder' => 'Выберете установщика',
                'fromgridroute' => 'Fregat/employee/index',
                'resultrequest' => 'Fregat/employee/selectinputemloyee',
                'thisroute' => $this->context->module->requestedRoute,
                'methodquery' => 'selectinput',
    ]));
    ?>

    <?=
    $form->field($model, 'installakt_date')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE,
        'options' => [
            'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
        ],
    ])
    ?>

    <?php ActiveForm::end(); ?>

    <?php
    if (!$model->isNewRecord) {
        echo DynaGrid::widget(Proc::DGopts([
                    'options' => ['id' => 'trOsnovgrid'],
                    'columns' => Proc::DGcols([
                        'buttonsfirst' => true,
                        'columns' => [
                            'idMattraffic.idMaterial.material_name',
                            'idMattraffic.idMaterial.material_inv',
                            'idMattraffic.mattraffic_number',
                            'tr_osnov_kab',
                            'idMattraffic.idMol.idperson.auth_user_fullname',
                            'idMattraffic.idMol.iddolzh.dolzh_name',
                        ],
                        'buttons' => [
                            'deleteajax' => ['Fregat/tr-osnov/delete', 'tr_osnov_id', 'trOsnovgrid'],
                        ],
                    ]),
                    'gridOptions' => [
                        'dataProvider' => $dataProviderOsn,
                        'filterModel' => $searchModelOsn,
                        'panel' => [
                            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-paperclip"></i> Перемещенные материальные ценности</h3>',
                            'before' => Html::a('<i class="glyphicon glyphicon-download"></i> Добавить материальную ценность', ['Fregat/tr-osnov/create',
                                'idinstallakt' => $model->primaryKey,
                                    ], ['class' => 'btn btn-success', 'data-pjax' => '0']),
                        ],
                    ]
        ]));

        echo DynaGrid::widget(Proc::DGopts([
                    'options' => ['id' => 'trMatgrid'],
                    'columns' => Proc::DGcols([
                        'buttonsfirst' => true,
                        'columns' => [
                            'idParent.material_name',
                            'idParent.material_inv',
                            'idMattraffic.idMaterial.material_name',
                            'idMattraffic.idMaterial.material_inv',
                            'idMattraffic.mattraffic_number',
                            'idMattraffic.idMol.idperson.auth_user_fullname',
                            'idMattraffic.idMol.iddolzh.dolzh_name',
                        ],
                        'buttons' => [
                            'deleteajax' => ['Fregat/tr-mat/delete', 'tr_mat_id', 'trMatgrid'],
                        ],
                    ]),
                    'gridOptions' => [
                        'dataProvider' => $dataProviderMat,
                        'filterModel' => $searchModelMat,
                        'panel' => [
                            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-paperclip"></i> Укомплектованные материальные ценности</h3>',
                            'before' => Html::a('<i class="glyphicon glyphicon-download"></i> Добавить материальную ценность', ['Fregat/tr-mat/create',
                                'idinstallakt' => $model->primaryKey,
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
                <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Installaktform']) ?>
                <?php
                if (!$model->isNewRecord)
                    echo Html::button('<i class="glyphicon glyphicon-list"></i> Скачать акт', ['id' => 'DownloadReport', 'class' => 'btn btn-info', 'onclick' => 'DownloadReport("' . Url::to(['Fregat/installakt/installakt-report']) . '", $(this)[0].id, {id: ' . $model->installakt_id . '} )']);
                ?>
            </div>
        </div> 
    </div>
</div>
