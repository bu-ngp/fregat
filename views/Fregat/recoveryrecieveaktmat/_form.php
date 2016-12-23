<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use app\models\Fregat\Osmotraktmat;
use app\models\Fregat\Material;
use app\models\Fregat\Build;
use app\models\Fregat\Dolzh;
use app\models\Fregat\TrOsnov;
use app\models\Config\Authuser;
use app\models\Fregat\Reason;
use app\models\Fregat\TrMatOsmotr;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Recoveryrecieveaktmat */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recoveryrecieveaktmat-form">

    <?=
    DynaGrid::widget(Proc::DGopts([
        'options' => ['id' => 'osmotrakt_rramat_grid'],
        'columns' => Proc::DGcols([
            'columns' => [
                'idTrMatOsmotr.idOsmotraktmat.osmotraktmat_id',
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
                'idTrMatOsmotr.idOsmotraktmat.idMaster.idperson.auth_user_fullname',
                'idTrMatOsmotr.idOsmotraktmat.idMaster.iddolzh.dolzh_name',
            ],
        ]),
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'panel' => [
                'heading' => '<i class="glyphicon glyphicon-file"></i> Акт осмотра материала',
            ],
        ]
    ])); ?>

    <?php
    $form = ActiveForm::begin([
        'id' => 'Recoveryrecieveaktmatform',
    ]);
    ?>

    <?=
    $form->field($model, 'recoveryrecieveaktmat_result')->textarea([
        'class' => 'form-control setsession',
        'maxlength' => 512,
        'rows' => 10,
        'style' => 'resize: none',
    ])
    ?>

    <?=
    $form->field($model, 'recoveryrecieveaktmat_repaired')->widget(Select2::classname(), [
        'hideSearch' => true,
        'data' => $model::VariablesValues('recoveryrecieveaktmat_repaired'),
        'pluginOptions' => [
            'allowClear' => true
        ],
        'options' => ['placeholder' => 'Выберете статус восстановления', 'class' => 'form-control setsession'],
        'theme' => Select2::THEME_BOOTSTRAP,
    ]);
    ?>

    <?=
    $form->field($model, 'recoveryrecieveaktmat_date')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE,
        'options' => [
            'options' => ['placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
        ],
    ])
    ?>

    <?php ActiveForm::end(); ?>

    <?=
    DynaGrid::widget(Proc::DGopts([
        'options' => ['id' => 'rramatDocfilesgrid'],
        'showPersonalize' => false,
        'columns' => Proc::DGcols([
            'columns' => [
                [
                    'attribute' => 'idDocfiles.docfiles_ext',
                    'format' => 'raw',
                    'value' => 'idDocfiles.docfiles_iconshow',
                    'contentOptions' => ['style' => 'width: 40px; text-align: center;'], // <-- right here
                    'filter' => false,
                ],
                [
                    'attribute' => 'idDocfiles.docfiles_name',
                    'format' => 'raw',
                    'value' => 'idDocfiles.docfiles_name_html',
                ],
                [
                    'attribute' => 'idDocfiles.docfiles_hash',
                    'visible' => false,
                ],
            ],
            'buttons' => array_merge(Yii::$app->user->can('DocfilesEdit') ? [
                'deleteajax' => ['Fregat/rramat-docfiles/delete', 'rramat_docfiles_id', 'rramatDocfilesgrid'],
            ] : []
            ),
        ]),
        'gridOptions' => [
            'dataProvider' => $dataProviderrramat,
            'filterModel' => $searchModelrramat,
            'panel' => [
                'heading' => '<i class="glyphicon glyphicon-file"></i> Прикрепленные файлы',
                'before' => (Yii::$app->user->can('RecoveryEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить из справочника прикрепленных файлов', ['Fregat/docfiles/index',
                    'foreignmodel' => 'RramatDocfiles',
                    'url' => $this->context->module->requestedRoute,
                    'field' => 'id_docfiles',
                    'id' => $model->primaryKey,
                ], ['class' => 'btn btn-success', 'data-pjax' => '0']) : ''),
            ],
        ]
    ])); ?>

    <?php
    $form2 = ActiveForm::begin([
        'id' => 'UploadDocform',
    ]);
    ?>

    <?= $form2->field($UploadFile, 'docFile')->widget(FileInput::classname(), [
        'pluginOptions' => [
            'uploadUrl' => Url::to(['Fregat/rramat-docfiles/create']),
            'uploadExtraData' => [
                'id_recoveryrecieveaktmat' => $_GET['id'],
            ],
            'dropZoneEnabled' => false,
            'previewZoomSettings' => [
                'image' => [
                    'width' => 'auto',
                    'height' => '100%',
                ],
            ],
            'showPreview' => false,
            'showUpload' => false,
            'showCancel' => false,
        ],
        'pluginEvents' => [
            "change" => 'function(event) { $("#uploaddocfile-docfile").fileinput("upload"); }',
            "fileuploaded" => 'function(event, data, previewId, index) { UploadedFiles("rramatDocfilesgrid", event, data); }'
        ],
    ]);
    ?>

    <?php ActiveForm::end(); ?>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">

                <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Recoveryrecieveaktmatform']) ?>
            </div>
        </div>
    </div>
</div>
