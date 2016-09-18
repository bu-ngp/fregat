<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use app\models\Fregat\Organ;
use app\models\Fregat\Recoveryrecieveakt;
use app\models\Fregat\Recoveryrecieveaktmat;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\bootstrap\ButtonDropdown;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Recoverysendakt */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recoverysendakt-form">

    <?php
    $form = ActiveForm::begin([
        'id' => 'Recoverysendaktform',
    ]);
    ?>

    <?= !$model->isNewRecord ? $form->field($model, 'recoverysendakt_id')->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) : '' ?>

    <?=
    $form->field($model, 'recoverysendakt_date')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE,
        'options' => [
            'options' => ['placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
        ],
    ])
    ?>

    <?=
    $form->field($model, 'id_organ')->widget(Select2::classname(), Proc::DGselect2([
        'model' => $model,
        'resultmodel' => new Organ,
        'fields' => [
            'keyfield' => 'id_organ',
            'resultfield' => 'organ_name',
        ],
        'placeholder' => 'Выберете организацию',
        'resultrequest' => 'Fregat/organ/selectinput',
        'thisroute' => $this->context->module->requestedRoute,
        'fromgridroute' => 'Fregat/organ/index',
    ]));
    ?>

    <?php ActiveForm::end(); ?>

    <?php
    if (!$model->isNewRecord) {
        echo $form->field(new app\models\Fregat\Osmotrakt, 'osmotrakt_id')->widget(Select2::classname(), [
            'options' => ['placeholder' => 'Введите инвентарный номер материальной ценности', 'class' => 'form-control'],
            'theme' => Select2::THEME_BOOTSTRAP,
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => \yii\helpers\Url::to(['Fregat/osmotrakt/selectinputforrecoverysendakt']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term} }'),
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            ],
            'addon' => [
                'append' => [
                    'content' => Html::button('<i class="glyphicon glyphicon-arrow-down"></i>  Вставить в таблицу', ['class' => 'btn btn-success', 'id' => 'addrecoveryrecieveakt', 'onclick' => 'AddOsmotrakt(' . $_GET['id'] . ')']),
                    'asButton' => true
                ]
            ],
        ])->label('Для быстрого добавления материальных ценностей');

        $recoveryrecieveakt_repaired = Recoveryrecieveakt::VariablesValues('recoveryrecieveakt_repaired');
        echo DynaGrid::widget(Proc::DGopts([
            'options' => ['id' => 'recoveryrecieveaktgrid'],
            'columns' => Proc::DGcols([
                'columns' => [
                    'idOsmotrakt.osmotrakt_id',
                    'idOsmotrakt.idTrosnov.idMattraffic.idMaterial.material_inv',
                    'idOsmotrakt.idTrosnov.idMattraffic.idMaterial.material_name',
                    'idOsmotrakt.idTrosnov.idMattraffic.idMol.idbuild.build_name',
                    'idOsmotrakt.idTrosnov.tr_osnov_kab',
                    'idOsmotrakt.idReason.reason_text',
                    'idOsmotrakt.osmotrakt_comment',
                    [
                        'attribute' => 'idOsmotrakt.idMaster.idperson.auth_user_fullname',
                        'label' => 'ФИО составителя акта осмотра',
                    ],
                    [
                        'attribute' => 'idOsmotrakt.osmotrakt_date',
                        'format' => 'date',
                        'visible' => false,
                    ],
                    [
                        'attribute' => 'idOsmotrakt.idMaster.iddolzh.dolzh_name',
                        'label' => 'Должность составителя акта осмотра',
                        'visible' => false,
                    ],
                    'recoveryrecieveakt_result',
                    [
                        'attribute' => 'recoveryrecieveakt_repaired',
                        'filter' => $recoveryrecieveakt_repaired,
                        'value' => function ($model) use ($recoveryrecieveakt_repaired) {
                            return isset($recoveryrecieveakt_repaired[$model->recoveryrecieveakt_repaired]) ? $recoveryrecieveakt_repaired[$model->recoveryrecieveakt_repaired] : '';
                        },
                    ],
                    [
                        'attribute' => 'recoveryrecieveakt_date',
                        'format' => 'date',
                    ],
                ],
                'buttons' => [
                    'update' => ['Fregat/recoveryrecieveakt/update'],
                    'deleteajax' => ['Fregat/recoveryrecieveakt/delete', 'recoveryrecieveakt_id', 'recoveryrecieveaktgrid'],
                ],
            ]),
            'gridOptions' => [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-compressed"></i> Восстанавливаемые материальные ценности</h3>',
                    'before' => Html::a('<i class="glyphicon glyphicon-download"></i> Добавить акт осмотра', ['Fregat/osmotrakt/forrecoveryrecieveakt',
                        'foreignmodel' => 'Recoveryrecieveakt',
                        'url' => $this->context->module->requestedRoute,
                        'field' => 'id_osmotrakt',
                        'id' => $model->primaryKey,
                    ], ['class' => 'btn btn-success', 'data-pjax' => '0']),
                ],
            ]
        ]));

        $recoveryrecieveaktmat_repaired = Recoveryrecieveaktmat::VariablesValues('recoveryrecieveaktmat_repaired');
        echo DynaGrid::widget(Proc::DGopts([
            'options' => ['id' => 'recoveryrecieveaktmatgrid'],
            'columns' => Proc::DGcols([
                'columns' => [
                    'idTrMatOsmotr.idOsmotraktmat.osmotraktmat_id',
                    [
                        'attribute' => 'idTrMatOsmotr.idOsmotraktmat.osmotraktmat_date',
                        'format' => 'date',
                    ],
                    'idTrMatOsmotr.idTrMat.idMattraffic.idMaterial.material_inv',
                    'idTrMatOsmotr.idTrMat.idMattraffic.idMaterial.material_name',
                    'idTrMatOsmotr.tr_mat_osmotr_number',
                    [
                        'attribute' => 'idTrMatOsmotr.idTrMat.idMattraffic.idMol.idperson.auth_user_fullname',
                        'label' => 'ФИО материально-ответственного лица',
                    ],
                    [
                        'attribute' => 'idTrMatOsmotr.idTrMat.idMattraffic.idMol.iddolzh.dolzh_name',
                        'label' => 'Должность материально-ответственного лица',
                    ],
                    'idTrMatOsmotr.idTrMat.idMattraffic.idMol.idbuild.build_name',
                    'idTrMatOsmotr.idReason.reason_text',
                    'idTrMatOsmotr.tr_mat_osmotr_comment',
                    'recoveryrecieveaktmat_result',
                    [
                        'attribute' => 'recoveryrecieveaktmat_repaired',
                        'filter' => $recoveryrecieveaktmat_repaired,
                        'value' => function ($model) use ($recoveryrecieveaktmat_repaired) {
                            return isset($recoveryrecieveaktmat_repaired[$model->recoveryrecieveaktmat_repaired]) ? $recoveryrecieveaktmat_repaired[$model->recoveryrecieveaktmat_repaired] : '';
                        },
                    ],
                    [
                        'attribute' => 'recoveryrecieveaktmat_date',
                        'format' => 'date',
                    ],
                ],
                'buttons' => [
                    'update' => ['Fregat/recoveryrecieveaktmat/update'],
                    'deleteajax' => ['Fregat/recoveryrecieveaktmat/delete', 'recoveryrecieveaktmat_id', 'recoveryrecieveaktmatgrid'],
                ],
            ]),
            'gridOptions' => [
                'dataProvider' => $dataProvidermat,
                'filterModel' => $searchModelmat,
                'panel' => [
                    'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-compressed"></i> Восстанавливаемые материалы</h3>',
                    'before' => Html::a('<i class="glyphicon glyphicon-download"></i> Добавить материала для восстановления', ['Fregat/tr-mat-osmotr/forrecoveryrecieveaktmat',
                        'foreignmodel' => 'Recoveryrecieveaktmat',
                        'url' => $this->context->module->requestedRoute,
                        'field' => 'id_tr_mat_osmotr',
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
                
                <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Recoverysendaktform']) ?>
                <?php
                if (!$model->isNewRecord) {
                    echo ButtonDropdown::widget([
                        'label' => '<i class="glyphicon glyphicon-list"></i> Скачать акт',
                        'encodeLabel' => false,
                        'id' => 'DownloadReport',
                        'dropdown' => [
                            'encodeLabels' => false,
                            'items' => [
                                ['label' => '<i class="glyphicon glyphicon-export"></i> Акт передачи материальных ценностей сторонней организации', 'url' => '#', 'linkOptions' => ['onclick' => 'DownloadReport("' . Url::to(['Fregat/recoverysendakt/recoverysendakt-report']) . '", "DownloadReport", {id: ' . $model->recoverysendakt_id . '} ); return false;']],
                                ['label' => '<i class="glyphicon glyphicon-import"></i> Акт получения материальных ценностей от сторонней организации', 'url' => '#', 'linkOptions' => ['onclick' => 'DownloadReport("' . Url::to(['Fregat/recoveryrecieveakt/recoveryrecieveakt-report']) . '", "DownloadReport", {id: ' . $model->recoverysendakt_id . '} ); return false;']],
                                ['label' => '<i class="glyphicon glyphicon-chevron-up"></i> Акт передачи материалов сторонней организации', 'url' => '#', 'linkOptions' => ['onclick' => 'DownloadReport("' . Url::to(['Fregat/recoverysendakt/recoverysendaktmat-report']) . '", "DownloadReport", {id: ' . $model->recoverysendakt_id . '} ); return false;']],
                                ['label' => '<i class="glyphicon glyphicon-chevron-down"></i> Акт получения материалов от сторонней организации', 'url' => '#', 'linkOptions' => ['onclick' => 'DownloadReport("' . Url::to(['Fregat/recoveryrecieveaktmat/recoveryrecieveaktmat-report']) . '", "DownloadReport", {id: ' . $model->recoverysendakt_id . '} ); return false;']],
                            ],
                        ],
                        'options' => ['class' => 'btn btn-info'],
                        'containerOptions' => ['style' => 'padding-right: 4px;'],
                    ]);
                    echo ButtonDropdown::widget([
                        'label' => '<i class="glyphicon glyphicon-send"></i> Отправить акт по электронной почте',
                        'encodeLabel' => false,
                        'id' => 'SendReport',
                        'dropdown' => [
                            'encodeLabels' => false,
                            'items' => [
                                ['label' => '<i class="glyphicon glyphicon-export"></i> Акт передачи материальных ценностей сторонней организации', 'url' => '#', 'linkOptions' => ['onclick' => 'SendReport("' . Url::to(['Fregat/recoverysendakt/recoverysendakt-reportsend']) . '", "SendReport", {id: ' . $model->recoverysendakt_id . ',emailfrom:"' . $emailfrom . '",emailto:"' . $model->idOrgan->organ_email . '",emailtheme:"' . addslashes($emailtheme) . '"} ); return false;']],
                                ['label' => '<i class="glyphicon glyphicon-chevron-up"></i> Акт передачи материалов сторонней организации', 'url' => '#', 'linkOptions' => ['onclick' => 'SendReport("' . Url::to(['Fregat/recoverysendakt/recoverysendaktmat-reportsend']) . '", "SendReport", {id: ' . $model->recoverysendakt_id . ',emailfrom:"' . $emailfrom . '",emailto:"' . $model->idOrgan->organ_email . '",emailtheme:"' . addslashes($emailtheme) . '"} ); return false;']],
                            ],
                        ],
                        'options' => ['class' => 'btn btn-success'],
                        'containerOptions' => ['style' => 'padding-right: 4px;'],
                    ]);
                }
                ?>
            </div>
        </div>
    </div>
</div>
