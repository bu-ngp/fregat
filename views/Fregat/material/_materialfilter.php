<?php

use app\models\Fregat\Grupa;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="materialfilter-form">
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
            <div class="panel-heading"><?= Html::encode('Материальная ценность') ?></div>
            <div class="panel-body">

                <?=
                $form->field($model, 'mol_fullname_material')->widget(Select2::classname(), array_merge(Proc::DGselect2([
                    'model' => $model,
                    'resultmodel' => new \app\models\Config\Authuser,
                    'placeholder' => 'Введите ФИО Материально-ответственного лица',
                    'setsession' => false,
                    'multiple' => [
                        'multipleshowall' => false,
                        'idvalue' => 'auth_user_id',
                    ],
                    'fields' => [
                        'keyfield' => 'mol_fullname_material',
                        'resultfield' => 'auth_user_fullname',
                    ],
                    'resultrequest' => 'Config/authuser/selectinput',
                    'thisroute' => $this->context->module->requestedRoute,
                ]), [
                    'addon' => [
                        'prepend' => [
                            'content' => Proc::SetTemplateForActiveFieldWithNOT($form, $model, 'mol_fullname_material'),
                        ],
                        'groupOptions' => [
                            'class' => 'notforselect2',
                        ],
                    ],
                ]));
                ?>

                <?= Proc::FilterFieldSelectSingle($form, $model, 'material_writeoff', 'Выберете условие') ?>

                <?=
                $form->field($model, 'mat_id_grupa')->widget(Select2::classname(), array_merge(Proc::DGselect2([
                    'model' => $model,
                    'resultmodel' => new Grupa,
                    'placeholder' => 'Введите группу материальной ценности',
                    'setsession' => false,
                    'multiple' => [
                        'multipleshowall' => false,
                        'idvalue' => 'grupa_id',
                    ],
                    'fields' => [
                        'keyfield' => 'mat_id_grupa',
                        'resultfield' => 'grupa_name',
                    ],
                    'resultrequest' => 'Fregat/grupa/selectinput',
                    'thisroute' => $this->context->module->requestedRoute,
                    'onlyAjax' => false,
                ]), [
                    'addon' => [
                        'prepend' => [
                            'content' => Proc::SetTemplateForActiveFieldWithNOT($form, $model, 'mat_id_grupa'),
                        ],
                        'groupOptions' => [
                            'class' => 'notforselect2',
                        ],
                    ],
                ]));
                ?>

                <?= $form->field($model, 'material_attachfiles_mark')->checkbox()->label(null, ['class' => 'control-label']); ?>

                <?= $form->field($model, 'not_material_attachfiles_mark')->checkbox()->label(null, ['class' => 'control-label']); ?>

                <?= $form->field($model, 'material_attachphoto_mark')->checkbox()->label(null, ['class' => 'control-label']); ?>

                <?= $form->field($model, 'material_attachdoc_mark')->checkbox()->label(null, ['class' => 'control-label']); ?>

                <?= $form->field($model, 'material_comment_mark')->checkbox()->label(null, ['class' => 'control-label']); ?>

            </div>
        </div>

        <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?> panelblock">
            <div class="panel-heading"><?= Html::encode('Перемещение материальных ценностей') ?></div>
            <div class="panel-body">

                <?=
                $form->field($model, 'mol_id_build')->widget(Select2::classname(), array_merge(Proc::DGselect2([
                    'model' => $model,
                    'resultmodel' => new \app\models\Fregat\Build,
                    'placeholder' => 'Введите здание',
                    'setsession' => false,
                    'multiple' => [
                        'multipleshowall' => false,
                        'idvalue' => 'build_id',
                    ],
                    'fields' => [
                        'keyfield' => 'mol_id_build',
                        'resultfield' => 'build_name',
                    ],
                    'resultrequest' => 'Fregat/build/selectinput',
                    'thisroute' => $this->context->module->requestedRoute,
                    'onlyAjax' => false,
                ]), [
                    'addon' => [
                        'prepend' => [
                            'content' => Proc::SetTemplateForActiveFieldWithNOT($form, $model, 'mol_id_build'),
                        ],
                        'groupOptions' => [
                            'class' => 'notforselect2',
                        ],
                    ],
                ]));
                ?>

                <?= $form->field($model, 'tr_osnov_kab')->textInput(['maxlength' => true, 'class' => 'form-control inputuppercase']) ?>

                <?= $form->field($model, 'tr_osnov_install_mark')->checkbox()->label(null, ['class' => 'control-label']); ?>

                <?= $form->field($model, 'tr_osnov_uninstall_mark')->checkbox()->label(null, ['class' => 'control-label']); ?>

                <?= $form->field($model, 'tr_mat_install_mark')->checkbox()->label(null, ['class' => 'control-label']); ?>

                <?= $form->field($model, 'tr_mat_uninstall_mark')->checkbox()->label(null, ['class' => 'control-label']); ?>

            </div>
        </div>

        <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?> panelblock">
            <div class="panel-heading"><?= Html::encode('Восстановление материальных ценностей') ?></div>
            <div class="panel-body">

                <?= $form->field($model, 'material_working_mark')->checkbox()->label(null, ['class' => 'control-label']); ?>

                <?= $form->field($model, 'material_recovery_attachfiles_mark')->checkbox()->label(null, ['class' => 'control-label']); ?>

            </div>
        </div>

        <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?> panelblock">
            <div class="panel-heading"><?= Html::encode('Аудит операций пользователя') ?></div>
            <div class="panel-body">

                <?= $form->field($model, 'mattraffic_username')->textInput(['maxlength' => true, 'class' => 'form-control inputuppercase']) ?>

                <?= Proc::FilterFieldDateRange($form, $model, 'mattraffic_lastchange') ?>

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
