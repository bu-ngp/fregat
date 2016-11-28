<?php

use app\models\Fregat\Grupa;
use app\models\Fregat\Material;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="installaktfilter-form">
    <div class="form-group">
        <div class="row">
            <div class="col-xs-12">
                <?=
                yii\bootstrap\Html::input('text', null, null, ['class' => 'form-control inputuppercase searchfilterform', 'placeholder' => 'ПОИСК...', 'autofocus' => true])
                ?>
            </div>
        </div>
    </div>

    <?php $form = ActiveForm::begin(['options' => ['id' => $model->formName() . '-form', 'data-pjax' => true]]); ?>
    <div class="insideforms">

        <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?> panelblock">
            <div class="panel-heading"><?= Html::encode('Перемещенные материальные ценности') ?></div>
            <div class="panel-body">

                <?=
                $form->field($model, 'mat_id_material')->widget(Select2::className(), Proc::DGselect2([
                    'model' => $model,
                    'resultmodel' => new Material,
                    'placeholder' => 'Введите инвентарный номер',
                    'setsession' => false,
                    'fields' => [
                        'keyfield' => 'mat_id_material',
                        'resultfield' => 'material_inv',
                    ],
                    'resultrequest' => 'Fregat/material/selectinput',
                    'thisroute' => $this->context->module->requestedRoute,
                    'methodquery' => 'selectinput'
                ]));
                ?>

                <?=
                $form->field($model, 'mol_id_person')->widget(Select2::className(), Proc::DGselect2([
                    'model' => $model,
                    'resultmodel' => new \app\models\Config\Authuser(),
                    'placeholder' => 'Введите ФИО материально-ответственного лица',
                    'setsession' => false,
                    'fields' => [
                        'keyfield' => 'mol_id_person',
                        'resultfield' => 'auth_user_fullname',
                    ],
                    'resultrequest' => 'Config/authuser/selectinput',
                    'thisroute' => $this->context->module->requestedRoute,
                ]));
                ?>

                <?=
                $form->field($model, 'tr_osnov_mol_id_build')->widget(Select2::classname(), array_merge(Proc::DGselect2([
                    'model' => $model,
                    'resultmodel' => new \app\models\Fregat\Build,
                    'placeholder' => 'Введите здание',
                    'setsession' => false,
                    'multiple' => [
                        'multipleshowall' => false,
                        'idvalue' => 'build_id',
                    ],
                    'fields' => [
                        'keyfield' => 'tr_osnov_mol_id_build',
                        'resultfield' => 'build_name',
                    ],
                    'resultrequest' => 'Fregat/build/selectinput',
                    'thisroute' => $this->context->module->requestedRoute,
                ]), [
                    'addon' => [
                        'prepend' => [
                            'content' => Proc::SetTemplateForActiveFieldWithNOT($form, $model, 'tr_osnov_mol_id_build'),
                        ],
                        'groupOptions' => [
                            'class' => 'notforselect2',
                        ],
                    ],
                ]));
                ?>

                <?= $form->field($model, 'tr_osnov_kab')->textInput(['maxlength' => true, 'class' => 'form-control inputuppercase']) ?>

            </div>
        </div>
        <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?> panelblock">
            <div class="panel-heading"><?= Html::encode('Укомплектованные материальные ценности') ?></div>
            <div class="panel-body">

                <?=
                $form->field($model, 'mat_id_material_trmat')->widget(Select2::className(), Proc::DGselect2([
                    'model' => $model,
                    'resultmodel' => new Material,
                    'placeholder' => 'Введите инвентарный номер',
                    'setsession' => false,
                    'fields' => [
                        'keyfield' => 'mat_id_material_trmat',
                        'resultfield' => 'material_inv',
                    ],
                    'resultrequest' => 'Fregat/material/selectinput',
                    'thisroute' => $this->context->module->requestedRoute,
                    'methodquery' => 'selectinput'
                ]));
                ?>

                <?=
                $form->field($model, 'mol_id_person_trmat')->widget(Select2::className(), Proc::DGselect2([
                    'model' => $model,
                    'resultmodel' => new \app\models\Config\Authuser(),
                    'placeholder' => 'Введите ФИО материально-ответственного лица',
                    'setsession' => false,
                    'fields' => [
                        'keyfield' => 'mol_id_person_trmat',
                        'resultfield' => 'auth_user_fullname',
                    ],
                    'resultrequest' => 'Config/authuser/selectinput',
                    'thisroute' => $this->context->module->requestedRoute,
                ]));
                ?>

                <?=
                $form->field($model, 'id_parent')->widget(Select2::className(), Proc::DGselect2([
                    'model' => $model,
                    'resultmodel' => new Material,
                    'placeholder' => 'Введите инвентарный номер',
                    'setsession' => false,
                    'fields' => [
                        'keyfield' => 'id_parent',
                        'resultfield' => 'material_inv',
                    ],
                    'resultrequest' => 'Fregat/material/selectinput',
                    'thisroute' => $this->context->module->requestedRoute,
                    'methodquery' => 'selectinput'
                ]));
                ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::button('<i class="glyphicon glyphicon-ok"></i> Применить', ['class' => 'btn btn-primary', 'id' => $model->formName() . '_apply']) ?>
                <?= Html::button('<i class="glyphicon glyphicon-remove"></i> Отмена', ['class' => 'btn btn-danger', 'id' => $model->formName() . '_close']) ?>
                <?= Html::button('<i class="glyphicon glyphicon-remove-sign"></i> Сброс', ['class' => 'btn btn-default', 'id' => $model->formName() . '_reset']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
