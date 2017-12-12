<?php

use app\models\Fregat\Cabinet;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use app\models\Fregat\Employee;
use app\models\Fregat\TrOsnov;
use app\models\Fregat\Reason;
use app\models\Fregat\Material;
use app\models\Fregat\Build;
use app\models\Fregat\Dolzh;
use app\models\Config\Authuser;
use app\models\Fregat\Mattraffic;
use \yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Osmotrakt */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="osmotrakt-form">

    <?php
    $form = ActiveForm::begin([
        'id' => 'Osmotraktform',
    ]);
    ?>

    <?= !$model->isNewRecord ? $form->field($model, 'osmotrakt_id')->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) : '' ?>

    <?=
    $form->field($model, 'osmotrakt_date')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE,
        'options' => [
            'options' => ['placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
        ],
    ])
    ?>

    <?php
    /* var_dump($model->errors);
      var_dump($InstallTrOsnov->errors);*/
    ?>

    <?=
    $form->field($model, 'id_tr_osnov', ['enableClientValidation' => false])->widget(Select2::classname(), array_merge(Proc::DGselect2([
        'model' => $model,
        'resultmodel' => new TrOsnov,
        'fields' => [
            'keyfield' => 'id_tr_osnov',
        ],
        'placeholder' => 'Введите инвентарный номер материальной ценности',
        'fromgridroute' => 'Fregat/tr-osnov/forosmotrakt',
        'resultrequest' => 'Fregat/tr-osnov/selectinputforosmotrakt',
        'thisroute' => $this->context->module->requestedRoute,
        'methodquery' => 'selectinputforosmotrakt',
    ]), [
        'pluginEvents' => [
            "select2:select" => "function() { FillInstaledMat(); }",
            "select2:unselect" => "function() { ClearInstaledMat(); }"
        ],
    ]));
    ?>

    <?= $form->field(Proc::RelatModelValue($model, 'idTrosnov.idMattraffic.idMaterial', new Material), 'material_name', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) ?>

    <?= $form->field(Proc::RelatModelValue($model, 'idTrosnov.idMattraffic.idMaterial', new Material), 'material_inv', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) ?>

    <?= $form->field(Proc::RelatModelValue($model, 'idTrosnov.idMattraffic.idMaterial', new Material), 'material_serial', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) ?>

    <?= $form->field(Proc::RelatModelValue($model, 'idTrosnov.idMattraffic.idMol.idbuild', new Build), 'build_name', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) ?>

    <?= $form->field(Proc::RelatModelValue($model, 'idTrosnov.idCabinet', new Cabinet), 'cabinet_name', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) ?>

    <?= $form->field(Proc::RelatModelValue($model, 'idTrosnov.idMattraffic.idMol.idperson', new Authuser), 'auth_user_fullname', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) ?>

    <?= $form->field(Proc::RelatModelValue($model, 'idTrosnov.idMattraffic.idMol.iddolzh', new Dolzh), 'dolzh_name', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) ?>

    <?php if ($model->isNewRecord): ?>

        <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
            <div class="panel-heading"><?= Html::encode('Акт установки материальной ценности') ?></div>
            <div class="panel-body">
                <div class="alert alert-warning" role="alert"><a data-toggle="collapse" href="#Newinstallakt">Этот блок
                        заполняется только, если материальная ценность не была установлена в кабинет, что позволит
                        автоматически создать акт установки материальной ценности.</a></div>
                <div id="Newinstallakt" class="panel-collapse collapse
                <?php
                if (!empty($InstallTrOsnov->id_mattraffic))
                    echo ' in';
                ?>">
                    <?=
                    $form->field($InstallTrOsnov, 'id_mattraffic', ['enableClientValidation' => false])->widget(Select2::classname(), array_merge(Proc::DGselect2([
                        'model' => $InstallTrOsnov,
                        'resultmodel' => new Mattraffic,
                        'fields' => [
                            'keyfield' => 'id_mattraffic',
                        ],
                        'placeholder' => 'Введите инвентарный номер материальной ценности',
                        'fromgridroute' => 'Fregat/mattraffic/forosmotrakt',
                        'resultrequest' => 'Fregat/osmotrakt/selectinputforosmotrakt',
                        'thisroute' => $this->context->module->requestedRoute,
                        'methodquery' => 'selectinputforosmotrakt',
                        'dopparams' => ['foreigndo' => 1],
                    ]), [
                        'pluginEvents' => [
                            "select2:select" => "function() { FillNewinstallakt(); }",
                            "select2:unselect" => "function() { ClearNewinstallakt(); }",
                        ],
                    ]));
                    ?>

                    <?= $form->field(new Material, 'material_id')->hiddenInput()->label(false) ?>

                    <?= $form->field(new Material, 'material_name', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control newinstallakt', 'disabled' => true]) ?>

                    <?= $form->field(new Material, 'material_writeoff', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control newinstallakt', 'disabled' => true]) ?>

                    <?= $form->field(new Authuser, 'auth_user_fullname', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control newinstallakt', 'disabled' => true])->label('ФИО материально-ответственного лица') ?>

                    <?= $form->field(new Dolzh, 'dolzh_name', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control newinstallakt', 'disabled' => true])->label('Должность материально-ответственного лица') ?>

                    <?= $form->field(new Build, 'build_name', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control newinstallakt', 'disabled' => true])->label('Здание материально-ответственного лица') ?>

                    <?= $form->field(new Mattraffic, 'mattraffic_number', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control newinstallakt', 'disabled' => true])->label('Количество у материально-ответственного лица') ?>

                    <?php
                    echo $form->field($InstallTrOsnov, 'mattraffic_number', ['enableClientValidation' => false])->widget(kartik\touchspin\TouchSpin::classname(), [
                        'options' => ['class' => 'form-control setsession'],
                        'pluginOptions' => [
                            'verticalbuttons' => true,
                            'min' => 0.001,
                            'max' => 10000000000,
                            'step' => 1,

                            'decimals' => 3,
                            'forcestepdivisibility' => 'none',
                        ],
                    ])->label('Количество для перемещения');
                    ?>

                    <?=
                    $form->field($InstallTrOsnov, 'id_cabinet')->widget(Select2::classname(), Proc::DGselect2([
                        'model' => $InstallTrOsnov,
                        'resultmodel' => new Cabinet,
                        'fields' => [
                            'keyfield' => 'id_cabinet',
                        ],
                        'disabled' => $model->isNewRecord,
                        'placeholder' => 'Введите кабинет',
                        'fromgridroute' => 'Fregat/cabinet/forinstallakt',
                        'resultrequest' => 'Fregat/cabinet/selectinput',
                        'thisroute' => $this->context->module->requestedRoute,
                        'methodquery' => 'selectinput',
                        'methodparams' => [
                            'id_mattraffic' => ['forInit' => $InstallTrOsnov->id_mattraffic, 'forJs' => '$("#installtrosnov-id_mattraffic").val()'],
                        ],
                    ]));
                    ?>

                    <?php
                    echo Yii::$app->user->can('MolEdit') ? Html::button('<i class="glyphicon glyphicon-list"></i> Сменить материально-ответственное лицо', ['onclick' => 'RedirectToChangeMol()', 'class' => 'btn btn-success']) : '';
                    ?>

                </div>
            </div>
        </div>

    <?php endif; ?>

    <?=
    $form->field($model, 'id_user', ['enableClientValidation' => false])->widget(Select2::classname(), Proc::DGselect2([
        'model' => $model,
        'resultmodel' => new Employee,
        'fields' => [
            'keyfield' => 'id_user',
            'resultfield' => 'idperson.auth_user_fullname',
        ],
        'placeholder' => 'Выберете пользователя',
        'fromgridroute' => 'Fregat/employee/index',
        'resultrequest' => 'Fregat/employee/selectinputemloyee',
        'thisroute' => $this->context->module->requestedRoute,
        'methodquery' => 'selectinputactive',
    ]));
    ?>

    <?=
    $form->field($model, 'id_master', ['enableClientValidation' => false])->widget(Select2::classname(), Proc::DGselect2([
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
        'methodquery' => 'selectinputactive',
    ]));
    ?>

    <?=
    $form->field($model, 'id_reason')->widget(Select2::classname(), Proc::DGselect2([
        'model' => $model,
        'resultmodel' => new Reason,
        'fields' => [
            'keyfield' => 'id_reason',
            'resultfield' => 'reason_text',
        ],
        'placeholder' => 'Выберете причину неисправности',
        'fromgridroute' => 'Fregat/reason/index',
        'resultrequest' => 'Fregat/reason/selectinput',
        'thisroute' => $this->context->module->requestedRoute,
        'onlyAjax' => false,
    ]));
    ?>

    <?=
    $form->field($model, 'osmotrakt_comment')->textarea([
        'class' => 'form-control setsession',
        'form' => $formname, 'maxlength' => 1024,
        'placeholder' => 'Введите дополнительную информацию о неисправности',
        'rows' => 10,
        'style' => 'resize: none',
    ]);
    ?>

    <?php ActiveForm::end(); ?>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">

                <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Osmotraktform']) ?>
                <?php
                if (!$model->isNewRecord)
                    echo Html::button('<i class="glyphicon glyphicon-list"></i> Скачать акт', ['id' => 'DownloadReport', 'class' => 'btn btn-info', 'onclick' => 'DownloadReport("' . Url::to(['Fregat/osmotrakt/osmotrakt-report']) . '", $(this)[0].id, {id: ' . $model->osmotrakt_id . '} )']);
                ?>
            </div>
        </div>
    </div>

</div>
