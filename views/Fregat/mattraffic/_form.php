<?php

use app\models\Config\Authuser;
use app\models\Fregat\Build;
use app\models\Fregat\Dolzh;
use app\models\Fregat\Employee;
use app\models\Fregat\Podraz;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Fregat\Matvid;
use app\models\Fregat\Izmer;
use kartik\select2\Select2;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\web\Session;
use kartik\datecontrol\DateControl;
use app\models\Fregat\Mattraffic;
use app\models\Fregat\Recoveryrecieveakt;
use app\models\Fregat\Recoveryrecieveaktmat;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Material */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mattraffic-form">
    <?php
    $form = ActiveForm::begin([
        'id' => 'MattrafficMolform',
        //'enableAjaxValidation' => true,
    ]);
    ?>
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Материальная ценность') ?></div>
        <div class="panel-body">

            <?= $form->field($Material, 'material_tip', ['enableClientValidation' => false])->dropDownList([0 => '', 1 => 'Основное средство', 2 => 'Материал'], ['class' => 'form-control', 'disabled' => true]) ?>

            <?=
            $form->field($Material, 'id_matvid', ['enableClientValidation' => false])->widget(Select2::classname(), Proc::DGselect2([
                'model' => $Material,
                'resultmodel' => new Matvid,
                'fields' => [
                    'keyfield' => 'id_matvid',
                    'resultfield' => 'matvid_name',
                ],
                'placeholder' => 'Выберете вид материальной ценности',
                'fromgridroute' => 'Fregat/matvid/index',
                'resultrequest' => 'Fregat/matvid/selectinput',
                'thisroute' => $this->context->module->requestedRoute,
                'disabled' => true,
                'setsession' => false,
            ]));
            ?>

            <?= $form->field($Material, 'material_name', ['enableClientValidation' => false])->textInput(['class' => 'form-control', 'disabled' => true]) ?>


            <?= $form->field($Material, 'material_inv', ['enableClientValidation' => false])->textInput(['class' => 'form-control', 'disabled' => true]) ?>

            <?= $form->field($Material, 'material_number', ['enableClientValidation' => false])->textInput(['class' => 'form-control', 'disabled' => true]) ?>


            <?=
            $form->field($Material, 'id_izmer')->widget(Select2::classname(), Proc::DGselect2([
                'model' => $Material,
                'resultmodel' => new Izmer,
                'fields' => [
                    'keyfield' => 'id_izmer',
                    'resultfield' => 'izmer_name',
                ],
                'placeholder' => 'Выберете единицу измерения',
                'fromgridroute' => 'Fregat/izmer/index',
                'resultrequest' => 'Fregat/izmer/selectinput',
                'thisroute' => $this->context->module->requestedRoute,
                'disabled' => true,
                'setsession' => false,
            ]));
            ?>

            <?= $form->field($Material, 'material_price', ['enableClientValidation' => false])->textInput(['class' => 'form-control', 'disabled' => true]) ?>

            <?= $form->field($Material, 'material_serial', ['enableClientValidation' => false])->textInput(['class' => 'form-control', 'disabled' => true]) ?>

            <?=
            $form->field($Material, 'material_release', ['enableClientValidation' => false])->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
                'options' => [
                    'options' => ['placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                ],
                'disabled' => true,
            ])
            ?>

        </div>
    </div>

    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Текущее материально-ответственное лицо') ?></div>
        <div class="panel-body">

            <?= $form->field(Proc::RelatModelValue($Employee, 'idperson', new Authuser), 'auth_user_fullname', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) ?>

            <?= $form->field(Proc::RelatModelValue($Employee, 'iddolzh', new Dolzh), 'dolzh_name', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) ?>

            <?= $form->field(Proc::RelatModelValue($Employee, 'idpodraz', new Podraz), 'podraz_name', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) ?>

            <?= $form->field(Proc::RelatModelValue($Employee, 'idbuild', new Build), 'build_name', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) ?>

        </div>
    </div>

    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Сменить материально-ответственное лицо на') ?></div>
        <div class="panel-body">

            <?=
            $form->field($model, 'mattraffic_date')->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
                'options' => [
                    'options' => ['placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                ],
                'disabled' => true,
            ])
            ?>

            <?=
            $form->field($model, 'id_mol')->widget(Select2::classname(), Proc::DGselect2([
                'model' => $model,
                'resultmodel' => new Employee,
                'fields' => [
                    'keyfield' => 'id_mol',
                ],
                'placeholder' => 'Выберете материально-ответственное лицо',
                'fromgridroute' => 'Fregat/employee/index',
                'resultrequest' => 'Fregat/employee/selectinputemloyee',
                'thisroute' => $this->context->module->requestedRoute,
                'methodquery' => 'selectinput',
            ]));
            ?>

        </div>
    </div>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Назад', Proc::GetPreviousURLBreadcrumbsFromSession(), ['class' => 'btn btn-info']) ?>
                <?= Html::submitButton('<i class="glyphicon glyphicon-plus"></i> Сменить', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
