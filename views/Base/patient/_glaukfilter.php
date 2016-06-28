<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="patientglaukfilter-form">
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
            <div class="panel-heading"><?= Html::encode('Паспорт пациента') ?></div>
            <div class="panel-body">
                <?= $form->field($model, 'patient_fam')->textInput(['maxlength' => true, 'class' => 'form-control inputuppercase']) ?>

                <?= $form->field($model, 'patient_im')->textInput(['maxlength' => true, 'class' => 'form-control inputuppercase']) ?>

                <?= $form->field($model, 'patient_ot')->textInput(['maxlength' => true, 'class' => 'form-control inputuppercase']) ?>

                <?= Proc::FilterFieldDate($form, $model, 'patient_dr') ?>

                <?=
                Proc::FilterFieldIntCondition($form, $model, 'patient_vozrast', [
                    'min' => 1,
                    'max' => 120,
                    'step' => 1,
                    'decimals' => 0,
                ])
                ?>

                <?= Proc::FilterFieldSelectSingle($form, $model, 'patient_pol', 'Выберете пол пациента') ?>

                <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?> panelblock">
                    <div class="panel-heading"><?= Html::encode('Адрес пациента') ?></div>
                    <div class="panel-body">
                        <?=
                        $form->field($model, 'fias_city')->widget(Select2::classname(), array_merge(Proc::DGselect2([
                                            'model' => $model,
                                            'resultmodel' => new \app\models\Base\Fias,
                                            'fields' => [
                                                'keyfield' => 'fias_city',
                                            ],
                                            'placeholder' => 'Введите населенный пункт',
                                            'resultrequest' => 'Base/fias/selectinputforcity',
                                            'thisroute' => $this->context->module->requestedRoute,
                                            'methodquery' => 'selectinputforcity',
                                        ]), [
                            'pluginEvents' => [
                                "select2:select" => "function() { FillCity(); }",
                                "select2:unselect" => "function() { ClearCity(); }"
                            ],
                        ]))->label('Населенный пункт');
                        ?>

                        <?=
                        $form->field($model, 'fias_street')->widget(Select2::classname(), Proc::DGselect2([
                                    'model' => $model,
                                    'resultmodel' => new \app\models\Base\Fias,
                                    'fields' => [
                                        'keyfield' => 'fias_street',
                                    ],
                                    'placeholder' => 'Введите улицу',
                                    'resultrequest' => 'Base/fias/selectinputforstreet',
                                    'thisroute' => $this->context->module->requestedRoute,
                                    'methodquery' => 'selectinputforstreet',
                                    'ajaxparams' => ['fias_city' => '$(\'select[name="PatientFilter[fias_city]"]\').val()'],
                                    'minimuminputlength' => 2,
                        ]))
                        ?>

                        <?= $form->field($model, 'patient_dom')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>

                        <?= $form->field($model, 'patient_korp')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>

                        <?= $form->field($model, 'patient_kvartira')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?> panelblock">
            <div class="panel-heading"><?= Html::encode('Карта глаукомного пациента') ?></div>
            <div class="panel-body">

                <?= $form->field($model, 'is_glauk_mark')->checkbox(); ?>

                <?= Proc::FilterFieldDateRange($form, $model, 'glaukuchet_uchetbegin') ?>

                <?= Proc::FilterFieldSelectMultiple($form, $model, 'glaukuchet_detect', 'Выберете вид выявления заболевания') ?>

                <?= $form->field($model, 'is_glaukuchet_mark')->checkbox(); ?>

                <?= Proc::FilterFieldSelectMultiple($form, $model, 'glaukuchet_deregreason', 'Выберете причину снятия с учета') ?>

                <?= Proc::FilterFieldDateRange($form, $model, 'glaukuchet_deregdate') ?>

                <?= Proc::FilterFieldSelectMultiple($form, $model, 'glaukuchet_stage', 'Выберете стадию глаукомы') ?>

                <?= Proc::FilterFieldDateRange($form, $model, 'glaukuchet_operdate') ?>

                <?= $form->field($model, 'glaukuchet_not_oper_mark')->checkbox(); ?>

                <?= Proc::FilterFieldSelectMultiple($form, $model, 'glaukuchet_invalid', 'Выберете группу инвалидности') ?>

                <?= $form->field($model, 'glaukuchet_not_invalid_mark')->checkbox(); ?>

                <?= Proc::FilterFieldDateRange($form, $model, 'glaukuchet_lastvisit') ?>

                <?= Proc::FilterFieldDateRange($form, $model, 'glaukuchet_lastmetabol') ?>

                <?= $form->field($model, 'glaukuchet_not_lastmetabol_mark')->checkbox(); ?>

                <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?> panelblock">
                    <div class="panel-heading"><?= Html::encode('Врач') ?></div>
                    <div class="panel-body">

                        <?=
                        $form->field($model, 'glaukuchet_id_employee')->widget(Select2::classname(), Proc::DGselect2([
                                    'model' => $model,
                                    'resultmodel' => new \app\models\Fregat\Employee,
                                    'fields' => [
                                        'keyfield' => 'glaukuchet_id_employee',
                                    ],
                                    'placeholder' => 'Введите врача',
                                    'resultrequest' => 'Glauk/glaukuchet/selectinputforvrach',
                                    'thisroute' => $this->context->module->requestedRoute,
                                    'methodquery' => 'selectinput',
                        ]));
                        ?>

                        <?=
                        $form->field($model, 'employee_id_dolzh')->widget(Select2::classname(), Proc::DGselect2([
                                    'model' => $model,
                                    'resultmodel' => new \app\models\Fregat\Dolzh,
                                    'placeholder' => 'Введите должность',
                                    'setsession' => false,
                                    'multiple' => [
                                        'multipleshowall' => false,
                                        'idvalue' => 'dolzh_id',
                                    ],
                                    'fields' => [
                                        'keyfield' => 'employee_id_dolzh',
                                        'resultfield' => 'dolzh_name',
                                    ],
                                    'resultrequest' => 'Fregat/dolzh/selectinput',
                                    'thisroute' => $this->context->module->requestedRoute,
                        ]));
                        ?>

                        <?=
                        $form->field($model, 'employee_id_podraz')->widget(Select2::classname(), Proc::DGselect2([
                                    'model' => $model,
                                    'resultmodel' => new \app\models\Fregat\Podraz,
                                    'placeholder' => 'Введите подразделение',
                                    'setsession' => false,
                                    'multiple' => [
                                        'multipleshowall' => false,
                                        'idvalue' => 'podraz_id',
                                    ],
                                    'fields' => [
                                        'keyfield' => 'employee_id_podraz',
                                        'resultfield' => 'podraz_name',
                                    ],
                                    'resultrequest' => 'Fregat/podraz/selectinput',
                                    'thisroute' => $this->context->module->requestedRoute,
                        ]));
                        ?>

                        <?=
                        $form->field($model, 'employee_id_build')->widget(Select2::classname(), Proc::DGselect2([
                                    'model' => $model,
                                    'resultmodel' => new \app\models\Fregat\Build,
                                    'placeholder' => 'Введите здание',
                                    'setsession' => false,
                                    'multiple' => [
                                        'multipleshowall' => false,
                                        'idvalue' => 'build_id',
                                    ],
                                    'fields' => [
                                        'keyfield' => 'employee_id_build',
                                        'resultfield' => 'build_name',
                                    ],
                                    'resultrequest' => 'Fregat/build/selectinput',
                                    'thisroute' => $this->context->module->requestedRoute,
                        ]));
                        ?>

                    </div>   
                </div>

                <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?> panelblock">
                    <div class="panel-heading"><?= Html::encode('Медикаментозная терапия') ?></div>
                    <div class="panel-body">

                        <?=
                        $form->field($model, 'glprep_id_preparat')->widget(Select2::classname(), Proc::DGselect2([
                                    'model' => $model,
                                    'resultmodel' => new app\models\Base\Preparat,
                                    'placeholder' => 'Введите название препарата',
                                    'setsession' => false,
                                    'multiple' => [
                                        'multipleshowall' => false,
                                        'idvalue' => 'preparat_id',
                                    ],
                                    'fields' => [
                                        'keyfield' => 'glprep_id_preparat',
                                        'resultfield' => 'preparat_name',
                                    ],
                                    'resultrequest' => 'Base/preparat/selectinput',
                                    'thisroute' => $this->context->module->requestedRoute,
                        ]));
                        ?>

                        <?= Proc::FilterFieldSelectMultiple($form, $model, 'glprep_rlocat', 'ыберете категорию льготного лекарственного обеспечения') ?>

                        <?= $form->field($model, 'glprep_not_preparat_mark')->checkbox(); ?>

                        <?= $form->field($model, 'glprep_preparat_mark')->checkbox(); ?>

                    </div>
                </div>

                <?= $form->field($model, 'glaukuchet_comment_mark')->checkbox(); ?>

                <?= $form->field($model, 'glaukuchet_comment')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>

            </div>   
        </div>

        <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?> panelblock">
            <div class="panel-heading"><?= Html::encode('Аудит операций пользователя') ?></div>
            <div class="panel-body">

                <?= $form->field($model, 'patient_username')->textInput(['maxlength' => true, 'class' => 'form-control inputuppercase']) ?>

                <?= Proc::FilterFieldDateRange($form, $model, 'patient_lastchange') ?>

                <?= $form->field($model, 'glaukuchet_username')->textInput(['maxlength' => true, 'class' => 'form-control inputuppercase']) ?>

                <?= Proc::FilterFieldDateRange($form, $model, 'glaukuchet_lastchange') ?>

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
