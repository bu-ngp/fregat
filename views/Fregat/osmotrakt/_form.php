<?php

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
            'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
        ],
    ])
    ?>

    <?=
    $form->field($model, 'id_tr_osnov')->widget(Select2::classname(), array_merge(Proc::DGselect2([
                        'model' => $model,
                        'resultmodel' => new TrOsnov,
                        'fields' => [
                            'keyfield' => 'id_tr_osnov',
                        ],
                        'placeholder' => 'Введите инвентарный номер материальной ценности',
                        'fromgridroute' => 'Fregat/tr_osnov/forosmotrakt',
                        'resultrequest' => 'Fregat/tr-osnov/selectinputforosmotrakt',
                        'thisroute' => $this->context->module->requestedRoute,
                        'methodquery' => 'selectinputforosmotrakt',
                    ]), [
                    /*  'pluginEvents' => [
                      "select2:select" => "function() { FillTrOsnov(); }",
                      "select2:unselect" => "function() { ClearTrOsnov(); }"
                      ], */
    ]));
    ?>

    <?= $form->field(Proc::RelatModelValue($model, 'idTrosnov.idMattraffic.idMaterial', new Material), 'material_name', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => true]) ?>

    <?= $form->field(Proc::RelatModelValue($model, 'idTrosnov.idMattraffic.idMaterial', new Material), 'material_inv', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => true]) ?>

    <?= $form->field(Proc::RelatModelValue($model, 'idTrosnov.idMattraffic.idMaterial', new Material), 'material_serial', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => true]) ?>

    <?= $form->field(Proc::RelatModelValue($model, 'idTrosnov.idMattraffic.idMol.idbuild', new Build), 'build_name', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => true]) ?>

    <?= $form->field(Proc::RelatModelValue($model, 'idTrosnov', new TrOsnov), 'tr_osnov_kab', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => true]) ?>

    <?= $form->field(Proc::RelatModelValue($model, 'idTrosnov.idMattraffic.idMol.idperson', new Authuser), 'auth_user_fullname', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => true]) ?>

    <?= $form->field(Proc::RelatModelValue($model, 'idTrosnov.idMattraffic.idMol.iddolzh', new Dolzh), 'dolzh_name', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => true]) ?>

    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Акт установки материальной ценности') ?></div>
        <div class="panel-body">
            <div class="alert alert-warning" role="alert">Этот блок заполняется только, если материальная ценность не была установлена в кабинет, что позволит автоматически создать акт установки материальной ценности.</div>

            <?=
            $form->field($Trosnov, 'id_mattraffic')->widget(Select2::classname(), array_merge(Proc::DGselect2([
                                'model' => $Trosnov,
                                'resultmodel' => Mattraffic,
                                'fields' => [
                                    'keyfield' => 'id_mattraffic',
                                ],
                                'placeholder' => 'Введите инвентарный номер материальной ценности',
                                'fromgridroute' => 'Fregat/mattraffic/forinstallakt',
                                'resultrequest' => 'Fregat/tr-osnov/selectinputfortrosnov',
                                'thisroute' => $this->context->module->requestedRoute,
                                'methodquery' => 'selectinputfortrosnov',
                                
                            ]), [
           /*     'pluginEvents' => [
                    "select2:select" => "function() { FillTrOsnov(); }",
                    "select2:unselect" => "function() { ClearTrOsnov(); }"
                ],*/
            ]));
            ?>

            <?= $form->field($Trosnov, 'tr_osnov_kab')->textInput(['maxlength' => true, 'class' => 'form-control setsession']) ?> 

        </div>
    </div>

    <?=
    $form->field($model, 'id_user')->widget(Select2::classname(), Proc::DGselect2([
                'model' => $model,
                'resultmodel' => new Employee,
                'fields' => [
                    'keyfield' => 'id_user',
                    'resultfield' => 'idperson.auth_user_fullname',
                ],
                'placeholder' => 'Выберете пользователя',
                'fromgridroute' => 'Fregat/employee/index',
                'resultrequest' => 'Fregat/employee/selectinputformaterial',
                'thisroute' => $this->context->module->requestedRoute,
                'methodquery' => 'selectinput',
    ]));
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
                'resultrequest' => 'Fregat/employee/selectinputformaterial',
                'thisroute' => $this->context->module->requestedRoute,
                'methodquery' => 'selectinput',
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
                <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Назад', Proc::GetPreviousURLBreadcrumbsFromSession(), ['class' => 'btn btn-info']) ?>
                <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Installaktform']) ?>
            </div>
        </div> 
    </div>

</div>
