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

<div class="removeakt-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'Removeaktform',
    ]);
    ?>

    <?= !$model->isNewRecord ? $form->field($model, 'removeakt_id')->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) : '' ?>

    <?=
    $form->field($model, 'id_remover')->widget(Select2::classname(), Proc::DGselect2([
                'model' => $model,
                'resultmodel' => new Employee,
                'fields' => [
                    'keyfield' => 'id_remover',
                    'resultfield' => 'idperson.auth_user_fullname',
                ],
                'placeholder' => 'Выберете демонтажника',
                'fromgridroute' => 'Fregat/employee/index',
                'resultrequest' => 'Fregat/employee/selectinputemloyee',
                'thisroute' => $this->context->module->requestedRoute,
                'methodquery' => 'selectinput',
    ]));
    ?>

    <?=
    $form->field($model, 'removeakt_date')->widget(DateControl::classname(), [
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
                    'options' => ['id' => 'trRmMatgrid'],
                    'columns' => Proc::DGcols([
                        'columns' => [
                            'idTrMat.idParent.material_name',
                            'idTrMat.idParent.material_inv',
                            'idTrMat.idParent.material_serial',
                            [
                                'attribute' => 'idTrMat.idMattraffic.idMaterial.material_name',
                                'label' => 'Наименование комплектующего',
                            ],
                            [
                                'attribute' => 'idTrMat.idMattraffic.idMaterial.material_inv',
                                'label' => 'Инвентарный номер комплектующего',
                            ],
                            'idTrMat.idMattraffic.mattraffic_number',
                            [
                                'attribute' => 'idTrMat.idMattraffic.idMol.idperson.auth_user_fullname',
                                'label' => 'ФИО МОЛ комплектующего',
                            ],
                            [
                                'attribute' => 'idTrMat.idMattraffic.idMol.iddolzh.dolzh_name',
                                'label' => 'Должность МОЛ комплектующего',
                            ],
                        ],
                        'buttons' => [
                            'deleteajax' => ['Fregat/tr-rm-mat/delete'],
                        ],
                    ]),
                    'gridOptions' => [
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'panel' => [
                            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-paperclip"></i> Снятие материальные ценности</h3>',
                            'before' => Html::a('<i class="glyphicon glyphicon-download"></i> Добавить материальную ценность', ['Fregat/tr-mat/fortrrmmat',
                                'foreignmodel' => 'TrRmMat',
                                'url' => $this->context->module->requestedRoute,
                                'field' => 'id_tr_mat',
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
                <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Removeaktform']) ?>
                <?php
                if (!$model->isNewRecord)
                    echo Html::button('<i class="glyphicon glyphicon-list"></i> Скачать акт', ['id' => 'DownloadReport', 'class' => 'btn btn-info', 'onclick' => 'DownloadReport("' . Url::to(['Fregat/removeakt/removeakt-report']) . '", $(this)[0].id, {id: ' . $model->removeakt_id . '} )']);
                ?>
            </div>
        </div> 
    </div>
</div>
