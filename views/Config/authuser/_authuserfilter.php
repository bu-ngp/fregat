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

<div class="authuserfilter-form">
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

                <?=
                $form->field($model, 'id_dolzh')->widget(Select2::classname(), array_merge(Proc::DGselect2([
                    'model' => $model,
                    'resultmodel' => new \app\models\Fregat\Dolzh,
                    'placeholder' => 'Введите должность',
                    'setsession' => false,
                    'multiple' => [
                        'multipleshowall' => false,
                        'idvalue' => 'dolzh_id',
                    ],
                    'fields' => [
                        'keyfield' => 'id_dolzh',
                        'resultfield' => 'dolzh_name',
                    ],
                    'resultrequest' => 'Fregat/dolzh/selectinput',
                    'thisroute' => $this->context->module->requestedRoute,
                    'onlyAjax' => false,
                ]), [
                    'addon' => [
                        'prepend' => [
                            'content' => Proc::SetTemplateForActiveFieldWithNOT($form, $model, 'id_dolzh'),
                        ],
                        'groupOptions' => [
                            'class' => 'notforselect2',
                        ],
                    ],
                ]));
                ?>

                <?=
                $form->field($model, 'id_podraz')->widget(Select2::classname(), array_merge(Proc::DGselect2([
                    'model' => $model,
                    'resultmodel' => new \app\models\Fregat\Podraz,
                    'placeholder' => 'Введите подразделение',
                    'setsession' => false,
                    'multiple' => [
                        'multipleshowall' => false,
                        'idvalue' => 'podraz_id',
                    ],
                    'fields' => [
                        'keyfield' => 'id_podraz',
                        'resultfield' => 'podraz_name',
                    ],
                    'resultrequest' => 'Fregat/podraz/selectinput',
                    'thisroute' => $this->context->module->requestedRoute,
                    'onlyAjax' => false,
                ]), [
                    'addon' => [
                        'prepend' => [
                            'content' => Proc::SetTemplateForActiveFieldWithNOT($form, $model, 'id_podraz'),
                        ],
                        'groupOptions' => [
                            'class' => 'notforselect2',
                        ],
                    ],
                ]));
                ?>

                <?=
                $form->field($model, 'id_build')->widget(Select2::classname(), array_merge(Proc::DGselect2([
                    'model' => $model,
                    'resultmodel' => new \app\models\Fregat\Build,
                    'placeholder' => 'Введите здание',
                    'setsession' => false,
                    'multiple' => [
                        'multipleshowall' => false,
                        'idvalue' => 'build_id',
                    ],
                    'fields' => [
                        'keyfield' => 'id_build',
                        'resultfield' => 'build_name',
                    ],
                    'resultrequest' => 'Fregat/build/selectinput',
                    'thisroute' => $this->context->module->requestedRoute,
                    'onlyAjax' => false,
                ]), [
                    'addon' => [
                        'prepend' => [
                            'content' => Proc::SetTemplateForActiveFieldWithNOT($form, $model, 'id_build'),
                        ],
                        'groupOptions' => [
                            'class' => 'notforselect2',
                        ],
                    ],
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
