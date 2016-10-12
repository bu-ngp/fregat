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

<div class="recoverysendaktfilter-form">
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
            <div class="panel-heading"><?= Html::encode('Основные') ?></div>
            <div class="panel-body">

                <?= $form->field($model, 'recoverysendakt_closed_mark')->checkbox()->label(null, ['class' => 'control-label']); ?>

            </div>
        </div>

        <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?> panelblock">
            <div class="panel-heading"><?= Html::encode('Материальная ценность') ?></div>
            <div class="panel-body">

                <?=
                $form->field($model, 'mat_id_material')->widget(Select2::classname(), Proc::DGselect2([
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
                $form->field($model, 'mol_id_person')->widget(Select2::classname(), Proc::DGselect2([
                    'model' => $model,
                    'resultmodel' => new \app\models\Config\Authuser(),
                    'placeholder' => 'Введите ФИО МОЛ',
                    'setsession' => false,
                    'fields' => [
                        'keyfield' => 'mol_id_person',
                        'resultfield' => 'auth_user_fullname',
                    ],
                    'resultrequest' => 'Config/authuser/selectinput',
                    'thisroute' => $this->context->module->requestedRoute,
                ]));
                ?>

            </div>
        </div>

    </div>
    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::Button('<i class="glyphicon glyphicon-ok"></i> Применить', ['class' => 'btn btn-primary', 'id' => $model->formName() . '_apply']) ?>
                <?= Html::Button('<i class="glyphicon glyphicon-remove"></i> Отмена', ['class' => 'btn btn-danger', 'id' => $model->formName() . '_close']) ?>
                <?= Html::Button('<i class="glyphicon glyphicon-remove-sign"></i> Сброс', ['class' => 'btn btn-default', 'id' => $model->formName() . '_reset']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
