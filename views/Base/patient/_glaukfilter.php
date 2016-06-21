<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="patientglaukfilter-form">
    <?php $form = ActiveForm::begin(['options' => ['id' => $model->formName() . '-form', 'data-pjax' => true]]); ?>
    <div class="insideforms">
        <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
            <div class="panel-heading"><?= Html::encode('Паспорт пациента') ?></div>
            <div class="panel-body">
                <?= $form->field($model, 'patient_fam')->textInput(['maxlength' => true, 'class' => 'form-control inputuppercase', 'autofocus' => true]) ?>

                <?= $form->field($model, 'patient_im')->textInput(['maxlength' => true, 'class' => 'form-control inputuppercase']) ?>

                <?= $form->field($model, 'patient_ot')->textInput(['maxlength' => true, 'class' => 'form-control inputuppercase']) ?>

                <?=
                $form->field($model, 'patient_dr')->widget(DateControl::classname(), [
                    'type' => DateControl::FORMAT_DATE,
                    'options' => [
                        'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                    ],
                    'saveOptions' => ['class' => 'form-control'],
                ])
                ?>

                <div class="form-group">     
                    <label class="control-label" for="patientfilter-patient_vozrast">Возраст пациента</label>
                    <div class="row"> 
                        <div class="col-xs-5">
                            <?=
                            $form->field($model, 'patient_vozrast_znak')->widget(Select2::classname(), [
                                'hideSearch' => true,
                                'data' => ['>=' => 'Больше или равно', '<=' => 'Меньше или равно', '=' => 'Равно'],
                                'options' => ['placeholder' => 'Выберете знак равенства', 'class' => 'form-control', 'style' => 'width; 215px;'],
                                'theme' => Select2::THEME_BOOTSTRAP,
                            ])->label(false);
                            ?>
                        </div>
                        <div class="col-xs-7">
                            <?=
                            $form->field($model, 'patient_vozrast')->widget(kartik\touchspin\TouchSpin::classname(), [
                                'options' => ['class' => 'form-control'],
                                'pluginOptions' => [
                                    'verticalbuttons' => true,
                                    'min' => 1,
                                    'max' => 120,
                                    'step' => 1,
                                    'decimals' => 0,
                                    'forcestepdivisibility' => 'none',
                                ]
                            ])->label(false);
                            ?>
                        </div>
                    </div>
                </div>

                <?=
                $form->field($model, 'patient_pol')->widget(Select2::classname(), [
                    'hideSearch' => true,
                    'data' => $model::VariablesValues('patient_pol'),
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'options' => ['placeholder' => 'Выберете пол пациента', 'class' => 'form-control'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                ]);
                ?>

                <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
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
        <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
            <div class="panel-heading"><?= Html::encode('Карта глаукомного пациента') ?></div>
            <div class="panel-body">
                <div class="form-group">     
                    <label class="control-label" for="patientfilter-glaukuchet_uchetbegin_beg"><?= $model->getAttributeLabel('glaukuchet_uchetbegin_beg') ?></label>
                    <div class="row">                         
                        <div class="col-xs-6">
                            <?=
                            $form->field($model, 'glaukuchet_uchetbegin_beg', [
                                'inputTemplate' => '<div class="input-group"><span class="input-group-addon">ОТ</span>{input}</div>'
                            ])->widget(DateControl::classname(), [
                                'type' => DateControl::FORMAT_DATE,
                                'options' => [
                                    'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                                ],
                                'saveOptions' => ['class' => 'form-control'],
                            ])->label(false);
                            ?>
                        </div>
                        <div class="col-xs-6">
                            <?=
                            $form->field($model, 'glaukuchet_uchetbegin_end', [
                                'inputTemplate' => '<div class="input-group"><span class="input-group-addon">ДО</span>{input}</div>'
                            ])->widget(DateControl::classname(), [
                                'type' => DateControl::FORMAT_DATE,
                                'options' => [
                                    'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                                ],
                                'saveOptions' => ['class' => 'form-control'],
                            ])->label(false);
                            ?>
                        </div>
                    </div>
                </div>

                <?=
                $form->field($model, 'glaukuchet_detect')->widget(Select2::classname(), [
                    'hideSearch' => true,
                    'data' => $model::VariablesValues('glaukuchet_detect'),
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'options' => ['placeholder' => 'Выберете вид выявления заболевания', 'class' => 'form-control', 'multiple' => true],
                    'theme' => Select2::THEME_BOOTSTRAP,
                ]);
                ?>

                <?=
                $form->field($model, 'glaukuchet_deregreason')->widget(Select2::classname(), [
                    'hideSearch' => true,
                    'data' => $model::VariablesValues('glaukuchet_deregreason'),
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'options' => ['placeholder' => 'Выберете причину снятия с учета', 'class' => 'form-control', 'multiple' => true],
                    'theme' => Select2::THEME_BOOTSTRAP,
                ]);
                ?>

                <div class="form-group">     
                    <label class="control-label" for="patientfilter-glaukuchet_deregdate_beg"><?= $model->getAttributeLabel('glaukuchet_deregdate_beg') ?></label>
                    <div class="row">                         
                        <div class="col-xs-6">
                            <?=
                            $form->field($model, 'glaukuchet_deregdate_beg', [
                                'inputTemplate' => '<div class="input-group"><span class="input-group-addon">ОТ</span>{input}</div>'
                            ])->widget(DateControl::classname(), [
                                'type' => DateControl::FORMAT_DATE,
                                'options' => [
                                    'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                                ],
                                'saveOptions' => ['class' => 'form-control'],
                            ])->label(false);
                            ?>
                        </div>
                        <div class="col-xs-6">
                            <?=
                            $form->field($model, 'glaukuchet_deregdate_end', [
                                'inputTemplate' => '<div class="input-group"><span class="input-group-addon">ДО</span>{input}</div>'
                            ])->widget(DateControl::classname(), [
                                'type' => DateControl::FORMAT_DATE,
                                'options' => [
                                    'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                                ],
                                'saveOptions' => ['class' => 'form-control'],
                            ])->label(false);
                            ?>
                        </div>
                    </div>
                </div>

                <?=
                $form->field($model, 'glaukuchet_stage')->widget(Select2::classname(), [
                    'hideSearch' => true,
                    'data' => $model::VariablesValues('glaukuchet_stage'),
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'options' => ['placeholder' => 'Выберете стадию глаукомы', 'class' => 'form-control', 'multiple' => true],
                    'theme' => Select2::THEME_BOOTSTRAP,
                ]);
                ?>

                <div class="form-group">     
                    <label class="control-label" for="patientfilter-glaukuchet_operdate_beg"><?= $model->getAttributeLabel('glaukuchet_operdate_beg') ?></label>
                    <div class="row">                         
                        <div class="col-xs-6">
                            <?=
                            $form->field($model, 'glaukuchet_operdate_beg', [
                                'inputTemplate' => '<div class="input-group"><span class="input-group-addon">ОТ</span>{input}</div>'
                            ])->widget(DateControl::classname(), [
                                'type' => DateControl::FORMAT_DATE,
                                'options' => [
                                    'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                                ],
                                'saveOptions' => ['class' => 'form-control'],
                            ])->label(false);
                            ?>
                        </div>
                        <div class="col-xs-6">
                            <?=
                            $form->field($model, 'glaukuchet_operdate_end', [
                                'inputTemplate' => '<div class="input-group"><span class="input-group-addon">ДО</span>{input}</div>'
                            ])->widget(DateControl::classname(), [
                                'type' => DateControl::FORMAT_DATE,
                                'options' => [
                                    'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                                ],
                                'saveOptions' => ['class' => 'form-control'],
                            ])->label(false);
                            ?>
                        </div>
                    </div>
                </div>

                <?=
                $form->field($model, 'glaukuchet_rlocat')->widget(Select2::classname(), [
                    'hideSearch' => true,
                    'data' => $model::VariablesValues('glaukuchet_rlocat'),
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'options' => ['placeholder' => 'Выберете категорию льготного лекарственного обеспечения', 'class' => 'form-control', 'multiple' => true],
                    'theme' => Select2::THEME_BOOTSTRAP,
                ]);
                ?>

                <?=
                $form->field($model, 'glaukuchet_invalid')->widget(Select2::classname(), [
                    'hideSearch' => true,
                    'data' => $model::VariablesValues('glaukuchet_invalid'),
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'options' => ['placeholder' => 'Выберете группу инвалидности', 'class' => 'form-control', 'multiple' => true],
                    'theme' => Select2::THEME_BOOTSTRAP,
                ]);
                ?>

                <div class="form-group">     
                    <label class="control-label" for="patientfilter-glaukuchet_lastvisit_beg"><?= $model->getAttributeLabel('glaukuchet_lastvisit_beg') ?></label>
                    <div class="row">                         
                        <div class="col-xs-6">
                            <?=
                            $form->field($model, 'glaukuchet_lastvisit_beg', [
                                'inputTemplate' => '<div class="input-group"><span class="input-group-addon">ОТ</span>{input}</div>'
                            ])->widget(DateControl::classname(), [
                                'type' => DateControl::FORMAT_DATE,
                                'options' => [
                                    'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                                ],
                                'saveOptions' => ['class' => 'form-control'],
                            ])->label(false);
                            ?>
                        </div>
                        <div class="col-xs-6">
                            <?=
                            $form->field($model, 'glaukuchet_lastvisit_end', [
                                'inputTemplate' => '<div class="input-group"><span class="input-group-addon">ДО</span>{input}</div>'
                            ])->widget(DateControl::classname(), [
                                'type' => DateControl::FORMAT_DATE,
                                'options' => [
                                    'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                                ],
                                'saveOptions' => ['class' => 'form-control'],
                            ])->label(false);
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">     
                    <label class="control-label" for="patientfilter-glaukuchet_lastmetabol_beg"><?= $model->getAttributeLabel('glaukuchet_lastmetabol_beg') ?></label>
                    <div class="row">                         
                        <div class="col-xs-6">
                            <?=
                            $form->field($model, 'glaukuchet_lastmetabol_beg', [
                                'inputTemplate' => '<div class="input-group"><span class="input-group-addon">ОТ</span>{input}</div>'
                            ])->widget(DateControl::classname(), [
                                'type' => DateControl::FORMAT_DATE,
                                'options' => [
                                    'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                                ],
                                'saveOptions' => ['class' => 'form-control'],
                            ])->label(false);
                            ?>
                        </div>
                        <div class="col-xs-6">
                            <?=
                            $form->field($model, 'glaukuchet_lastmetabol_end', [
                                'inputTemplate' => '<div class="input-group"><span class="input-group-addon">ДО</span>{input}</div>'
                            ])->widget(DateControl::classname(), [
                                'type' => DateControl::FORMAT_DATE,
                                'options' => [
                                    'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                                ],
                                'saveOptions' => ['class' => 'form-control'],
                            ])->label(false);
                            ?>
                        </div>
                    </div>
                </div>

                <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
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
                                        'idvalues' => 'dolzh_id',
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
                                    'fields' => [
                                        'keyfield' => 'employee_id_podraz',
                                        'resultfield' => 'podraz_name',
                                        'showresultfields' => ['podraz_id', 'podraz_name'],
                                    ],
                                    'resultrequest' => 'Fregat/podraz/selectinput',
                                    'thisroute' => $this->context->module->requestedRoute,
                                    'options' => ['placeholder' => 'Введите подразделение', 'class' => 'form-control', 'multiple' => true],
                                    'multipleshowall' => false,
                        ]));
                        ?>

                        <?=
                        $form->field($model, 'employee_id_build')->widget(Select2::classname(), Proc::DGselect2([
                                    'model' => $model,
                                    'resultmodel' => new \app\models\Fregat\Build,
                                    'fields' => [
                                        'keyfield' => 'employee_id_build',
                                        'resultfield' => 'build_name',
                                        'showresultfields' => ['build_id', 'build_name'],
                                    ],
                                    'resultrequest' => 'Fregat/build/selectinput',
                                    'thisroute' => $this->context->module->requestedRoute,
                                    'options' => ['placeholder' => 'Введите здание', 'class' => 'form-control', 'multiple' => true],
                                    'multipleshowall' => false,
                        ]));
                        ?>

                    </div>   
                </div>

            </div>   
        </div>

        <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
            <div class="panel-heading"><?= Html::encode('Аудит операций пользователя') ?></div>
            <div class="panel-body">

                <?= $form->field($model, 'patient_username')->textInput(['maxlength' => true, 'class' => 'form-control inputuppercase']) ?>

                <div class="form-group">     
                    <label class="control-label" for="patientfilter-patient_lastchange_beg"><?= $model->getAttributeLabel('patient_lastchange_beg') ?></label>
                    <div class="row">                         
                        <div class="col-xs-6">
                            <?=
                            $form->field($model, 'patient_lastchange_beg', [
                                'inputTemplate' => '<div class="input-group"><span class="input-group-addon">ОТ</span>{input}</div>'
                            ])->widget(DateControl::classname(), [
                                'type' => DateControl::FORMAT_DATE,
                                'options' => [
                                    'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                                ],
                                'saveOptions' => ['class' => 'form-control'],
                            ])->label(false);
                            ?>
                        </div>
                        <div class="col-xs-6">
                            <?=
                            $form->field($model, 'patient_lastchange_end', [
                                'inputTemplate' => '<div class="input-group"><span class="input-group-addon">ДО</span>{input}</div>'
                            ])->widget(DateControl::classname(), [
                                'type' => DateControl::FORMAT_DATE,
                                'options' => [
                                    'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                                ],
                                'saveOptions' => ['class' => 'form-control'],
                            ])->label(false);
                            ?>
                        </div>
                    </div>
                </div>

                <?= $form->field($model, 'glaukuchet_username')->textInput(['maxlength' => true, 'class' => 'form-control inputuppercase']) ?>

                <div class="form-group">     
                    <label class="control-label" for="patientfilter-glaukuchet_lastchange_beg"><?= $model->getAttributeLabel('glaukuchet_lastchange_beg') ?></label>
                    <div class="row">                         
                        <div class="col-xs-6">
                            <?=
                            $form->field($model, 'glaukuchet_lastchange_beg', [
                                'inputTemplate' => '<div class="input-group"><span class="input-group-addon">ОТ</span>{input}</div>'
                            ])->widget(DateControl::classname(), [
                                'type' => DateControl::FORMAT_DATE,
                                'options' => [
                                    'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                                ],
                                'saveOptions' => ['class' => 'form-control'],
                            ])->label(false);
                            ?>
                        </div>
                        <div class="col-xs-6">
                            <?=
                            $form->field($model, 'glaukuchet_lastchange_end', [
                                'inputTemplate' => '<div class="input-group"><span class="input-group-addon">ДО</span>{input}</div>'
                            ])->widget(DateControl::classname(), [
                                'type' => DateControl::FORMAT_DATE,
                                'options' => [
                                    'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                                ],
                                'saveOptions' => ['class' => 'form-control'],
                            ])->label(false);
                            ?>
                        </div>
                    </div>
                </div>


            </div> 
        </div>

    </div>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::Button('<i class="glyphicon glyphicon-ok"></i> Применить', ['class' => 'btn btn-primary', 'id' => $model->formName() . '_apply']) ?>
                <?= Html::Button('<i class="glyphicon glyphicon-remove"></i> Отмена', ['class' => 'btn btn-danger', 'id' => $model->formName() . '_close']) ?>
            </div>
        </div> 
    </div>

    <?php ActiveForm::end(); ?>
</div>
