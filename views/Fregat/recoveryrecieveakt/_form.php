<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use app\models\Fregat\Osmotrakt;
use app\models\Fregat\Material;
use app\models\Fregat\Build;
use app\models\Fregat\TrOsnov;
use app\models\Config\Authuser;
use app\models\Fregat\Reason;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Recoveryrecieveakt */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recoveryrecieveakt-form">

    <?=
    DynaGrid::widget(Proc::DGopts([
        'options' => ['id' => 'osmotrakt_rra_grid'],
        'columns' => Proc::DGcols([
            'columns' => [
                'idOsmotrakt.osmotrakt_id',
                'idOsmotrakt.idTrosnov.idMattraffic.idMaterial.material_inv',
                'idOsmotrakt.idTrosnov.idMattraffic.idMaterial.material_name',
                [
                    'attribute' => 'idOsmotrakt.idTrosnov.idMattraffic.idMol.idperson.auth_user_fullname',
                    'label' => 'ФИО материально-ответственного лица',
                ],
                [
                    'attribute' => 'idOsmotrakt.idTrosnov.idMattraffic.idMol.iddolzh.dolzh_name',
                    'label' => 'Должность материально-ответственного лица',
                ],
                'idOsmotrakt.idTrosnov.idMattraffic.idMol.idbuild.build_name',
                'idOsmotrakt.idTrosnov.tr_osnov_kab',
                'idOsmotrakt.idReason.reason_text',
                'idOsmotrakt.osmotrakt_comment',
                'idOsmotrakt.idMaster.idperson.auth_user_fullname',
                'idOsmotrakt.idMaster.iddolzh.dolzh_name',
            ],
        ]),
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'panel' => [
                'heading' => '<i class="glyphicon glyphicon-file"></i> Акт осмотра материальной ценности',
            ],
        ]
    ])); ?>

    <?php
    $form = ActiveForm::begin([
        'id' => 'Recoveryrecieveaktform',
    ]);
    ?>

    <?=
    $form->field($model, 'recoveryrecieveakt_result')->textarea([
        'class' => 'form-control setsession',
        'maxlength' => 512,
        'rows' => 10,
        'style' => 'resize: none',
    ])
    ?>

    <?=
    $form->field($model, 'recoveryrecieveakt_repaired')->widget(Select2::classname(), [
        'hideSearch' => true,
        'data' => $model::VariablesValues('recoveryrecieveakt_repaired'),
        'pluginOptions' => [
            'allowClear' => true
        ],
        'options' => ['placeholder' => 'Выберете статус восстановления', 'class' => 'form-control setsession'],
        'theme' => Select2::THEME_BOOTSTRAP,
    ]);
    ?>

    <?=
    $form->field($model, 'recoveryrecieveakt_date')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE,
        'options' => [
            'options' => ['placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
        ],
    ])
    ?>

    <?php ActiveForm::end(); ?>

    <?=
    DynaGrid::widget(Proc::DGopts([
        'options' => ['id' => 'rraDocfilesgrid'],
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
                'deleteajax' => ['Fregat/rra-docfiles/delete', 'rra_docfiles_id', 'rraDocfilesgrid'],
            ] : []
            ),
        ]),
        'gridOptions' => [
            'dataProvider' => $dataProviderrra,
            'filterModel' => $searchModelrra,
            'panel' => [
                'heading' => '<i class="glyphicon glyphicon-file"></i> Прикрепленные файлы',
            ],
        ]
    ])); ?>

    <?php
    $form2 = ActiveForm::begin([
        'id' => 'UploadDocform',
    ]);
    ?>

    <?php
    echo $form2->field($UploadFile, 'docFile')->widget(FileInput::classname(), [
        'pluginOptions' => [
            'uploadUrl' => Url::to(['Fregat/rra-docfiles/create']),
            'uploadExtraData' => [
                'id_recoveryrecieveakt' => $_GET['id'],
            ],
            'dropZoneEnabled' => false,
            'previewZoomSettings' => [
                'image' => [
                    'width' => 'auto',
                    'height' => '100%',
                ],
            ],
        ],
        'pluginEvents' => [
            "fileuploaded" => 'function(event, data, previewId, index) { UploadedFiles("rraDocfilesgrid", event, data); }'
        ],
    ]);
    ?>

    <?php ActiveForm::end(); ?>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">

                <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Recoveryrecieveaktform']) ?>
            </div>
        </div>
    </div>
</div>
