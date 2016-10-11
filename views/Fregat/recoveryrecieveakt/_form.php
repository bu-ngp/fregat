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

    <?php
    $form = ActiveForm::begin([
        'id' => 'Recoveryrecieveaktform',
    ]);
    ?>

    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Акт осмотра материальной ценности') ?></div>
        <div class="panel-body">
            <?= $form->field(Proc::RelatModelValue($model, 'idOsmotrakt', new Osmotrakt), 'osmotrakt_id', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) ?>
            <?= $form->field(Proc::RelatModelValue($model, 'idOsmotrakt.idTrosnov.idMattraffic.idMaterial', new Material), 'material_inv', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) ?>
            <?= $form->field(Proc::RelatModelValue($model, 'idOsmotrakt.idTrosnov.idMattraffic.idMaterial', new Material), 'material_name', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) ?>
            <?= $form->field(Proc::RelatModelValue($model, 'idOsmotrakt.idTrosnov.idMattraffic.idMol.idbuild', new Build), 'build_name', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) ?>
            <?= $form->field(Proc::RelatModelValue($model, 'idOsmotrakt.idTrosnov', new TrOsnov), 'tr_osnov_kab', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) ?>
            <?=
            $form->field(Proc::RelatModelValue($model, 'idOsmotrakt', new Osmotrakt), 'osmotrakt_comment', ['enableClientValidation' => false])->textarea([
                'class' => 'form-control',
                'maxlength' => 512,
                'rows' => 10,
                'disabled' => true,
                'style' => 'resize: none',
            ])
            ?>
            <?= $form->field(Proc::RelatModelValue($model, 'idOsmotrakt.idReason', new Reason), 'reason_text', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) ?>
            <?= $form->field(Proc::RelatModelValue($model, 'idOsmotrakt.idMaster.idperson', new Authuser), 'auth_user_fullname', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true])->label('Составитель акта осмотра') ?>
        </div>
    </div>

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

    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Прикрепить файл') ?></div>
        <div class="panel-body">

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
                ]
            ]);
            ?>

            <?php ActiveForm::end(); ?>

            <?=
            DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'rraDocfilesgrid'],
                'columns' => Proc::DGcols([
                    'columns' => [
                        'idDocfiles.docfiles_ext',
                        'idDocfiles.docfiles_name',
                        [
                            'attribute' => 'idDocfiles.docfiles_hash',
                            'visible' => false,
                        ],
                    ],
                    'buttons' => array_merge(Yii::$app->user->can('DocfilesEdit') ? [
                        'deleteajax' => ['Fregat/rra-docfiles/delete'],
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
        </div>
    </div>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">

                <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Recoveryrecieveaktform']) ?>
            </div>
        </div>
    </div>
</div>
