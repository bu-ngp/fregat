<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use kartik\dynagrid\DynaGrid;
use kartik\datecontrol\DateControl;
use app\func\Proc;
use yii\web\Session;

/* @var $this yii\web\View */
/* @var $model app\models\Base\Patient */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="patient-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'Patientform',
    ]);

    $formname = 'Patientform';
    ?>
    <?php ActiveForm::end(); ?>

    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Паспорт пациента') ?></div>
        <div class="panel-body">

            <?= $form->field($model, 'patient_fam')->textInput(['maxlength' => true, 'class' => 'form-control setsession inputuppercase', 'autofocus' => true, 'form' => $formname]) ?>

            <?= $form->field($model, 'patient_im')->textInput(['maxlength' => true, 'class' => 'form-control setsession inputuppercase', 'form' => $formname]) ?>

            <?= $form->field($model, 'patient_ot')->textInput(['maxlength' => true, 'class' => 'form-control setsession inputuppercase', 'form' => $formname]) ?>

            <?=
            $form->field($model, 'patient_dr')->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
                'options' => [
                    'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                ],
                'saveOptions' => ['class' => 'form-control setsession', 'form' => $formname],
            ])
            ?>

            <?=
            $form->field($model, 'patient_pol')->widget(Select2::classname(), [
                'hideSearch' => true,
                'data' => [1 => 'Мужской', 2 => 'Женский'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'options' => ['placeholder' => 'Выберете пол пациента', 'class' => 'form-control setsession', 'form' => $formname],
                'theme' => Select2::THEME_BOOTSTRAP,
            ]);
            ?>
        </div>
    </div>
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Адрес пациента') ?></div>
        <div class="panel-body">

            <?=
            $form->field($Fias, 'AOGUID')->widget(Select2::classname(), array_merge(Proc::DGselect2([
                                'model' => $Fias,
                                'resultmodel' => new \app\models\Base\Fias,
                                'fields' => [
                                    'keyfield' => 'AOGUID',
                                ],
                                'placeholder' => 'Введите населенный пункт',
                                'resultrequest' => 'Base/fias/selectinputforcity',
                                'thisroute' => $this->context->module->requestedRoute,
                                'methodquery' => 'selectinputforcity',
                                'form' => $formname,
                            ]), [
                'pluginEvents' => [
                    "select2:select" => "function() { FillCity(); }",
                    "select2:unselect" => "function() { ClearCity(); }"
                ],
            ]))->label('Населенный пункт');
            ?>

            <?=
            $form->field($model, 'id_fias')->widget(Select2::classname(), array_merge(Proc::DGselect2([
                                'model' => $model,
                                'resultmodel' => new \app\models\Base\Fias,
                                'fields' => [
                                    'keyfield' => 'id_fias',
                                ],
                                'placeholder' => 'Введите улицу',
                                'resultrequest' => 'Base/fias/selectinputforstreet',
                                'thisroute' => $this->context->module->requestedRoute,
                                'methodquery' => 'selectinputforstreet',
                                'ajaxparams' => ['fias_city' => '$(\'select[name="Fias[AOGUID]"]\').val()'],
                                'minimuminputlength' => 2,
                                'form' => $formname,
                            ]), [
                            /*  'pluginEvents' => [
                              "select2:select" => "function() { FillTrOsnov(); }",
                              "select2:unselect" => "function() { ClearTrOsnov(); }"
                              ], */
            ]))
            ?>

            <?= $form->field($model, 'patient_dom')->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'form' => $formname]) ?>

            <?= $form->field($model, 'patient_korp')->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'form' => $formname]) ?>

            <?= $form->field($model, 'patient_kvartira')->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'form' => $formname]) ?>

        </div>
    </div>
    <?php if ($patienttype === 'glauk'): ?>
        <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
            <div class="panel-heading"><?= Html::encode('Карта глаукомного пациента') ?></div>
            <div class="panel-body">
                <?=
                $form->field($dopparams['Glaukuchet'], 'glaukuchet_uchetbegin')->widget(DateControl::classname(), [
                    'type' => DateControl::FORMAT_DATE,
                    'options' => [
                        'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                    ],
                    'saveOptions' => ['class' => 'form-control setsession', 'form' => $formname],
                ])
                ?>

                <?=
                $form->field($dopparams['Glaukuchet'], 'glaukuchet_lastvisit')->widget(DateControl::classname(), [
                    'type' => DateControl::FORMAT_DATE,
                    'options' => [
                        'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                    ],
                    'saveOptions' => ['class' => 'form-control setsession', 'form' => $formname],
                ])
                ?>

                <?=
                $form->field($dopparams['Glaukuchet'], 'id_employee')->widget(Select2::classname(), Proc::DGselect2([
                            'model' => $dopparams['Glaukuchet'],
                            'resultmodel' => new \app\models\Fregat\Employee,
                            'fields' => [
                                'keyfield' => 'id_employee',
                            ],
                            'placeholder' => 'Введите врача',
                            'fromgridroute' => 'Fregat/employee/index',
                            'resultrequest' => 'Glauk/glaukuchet/selectinputforvrach',
                            'thisroute' => $this->context->module->requestedRoute,
                            'methodquery' => 'selectinput',
                            'dopparams' => ['patienttype' => $patienttype],
                            'form' => $formname,
                ]));
                ?>

                <?=
                $form->field($dopparams['Glaukuchet'], 'id_class_mkb')->widget(Select2::classname(), Proc::DGselect2([
                            'model' => $dopparams['Glaukuchet'],
                            'resultmodel' => new app\models\Base\Classmkb,
                            'fields' => [
                                'keyfield' => 'id_class_mkb',
                            ],
                            'placeholder' => 'Введите диагноз',
                            'fromgridroute' => 'Base/classmkb/indexglauk',
                            'resultrequest' => 'Base/classmkb/selectinputfordiag',
                            'thisroute' => $this->context->module->requestedRoute,
                            'methodquery' => 'selectinput',
                            'dopparams' => ['patienttype' => $patienttype],
                            'form' => $formname,
                ]));
                ?>

                <?=
                $form->field($dopparams['Glaukuchet'], 'glaukuchet_detect')->widget(Select2::classname(), [
                    'hideSearch' => true,
                    'data' => [1 => 'При обращении за лечением', 2 => 'При обращении по диспансеризации'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'options' => ['placeholder' => 'Выберете вид выявления заболевания', 'class' => 'form-control setsession', 'form' => $formname],
                    'theme' => Select2::THEME_BOOTSTRAP,
                ]);
                ?>

                <?=
                $form->field($dopparams['Glaukuchet'], 'glaukuchet_stage')->widget(Select2::classname(), [
                    'hideSearch' => true,
                    'data' => [1 => 'I стадия', 2 => 'II стадия', 3 => 'III стадия', 4 => 'IV стадия'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'options' => ['placeholder' => 'Выберете вид выявления заболевания', 'class' => 'form-control setsession', 'form' => $formname],
                    'theme' => Select2::THEME_BOOTSTRAP,
                ]);
                ?>

                <?=
                $form->field($dopparams['Glaukuchet'], 'glaukuchet_operdate')->widget(DateControl::classname(), [
                    'type' => DateControl::FORMAT_DATE,
                    'options' => [
                        'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                    ],
                    'saveOptions' => ['class' => 'form-control setsession', 'form' => $formname],
                ])
                ?>

                <?=
                $form->field($dopparams['Glaukuchet'], 'glaukuchet_invalid')->widget(Select2::classname(), [
                    'hideSearch' => true,
                    'data' => [1 => 'I группа', 2 => 'II группа', 3 => 'III группа'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'options' => ['placeholder' => 'Выберете вид выявления заболевания', 'class' => 'form-control setsession', 'form' => $formname],
                    'theme' => Select2::THEME_BOOTSTRAP,
                ]);
                ?>

                <?=
                $form->field($dopparams['Glaukuchet'], 'glaukuchet_lastmetabol')->widget(DateControl::classname(), [
                    'type' => DateControl::FORMAT_DATE,
                    'options' => [
                        'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                    ],
                    'saveOptions' => ['class' => 'form-control setsession', 'form' => $formname],
                ])
                ?>

                <?=
                $form->field($dopparams['Glaukuchet'], 'glaukuchet_rlocat')->widget(Select2::classname(), [
                    'hideSearch' => true,
                    'data' => [1 => 'Федеральная', 2 => 'Региональная'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'options' => ['placeholder' => 'Выберете вид выявления заболевания', 'class' => 'form-control setsession', 'form' => $formname],
                    'theme' => Select2::THEME_BOOTSTRAP,
                ]);
                ?>

                <?php if ($model->isNewRecord || $dopparams['Glaukuchet']->isNewRecord): ?>
                    <div class="alert alert-warning" role="alert">Для назначения лекарственных препаратов сохраните карту пациента.</div>
                <?php endif; ?>

                <?php
                if (!$model->isNewRecord && !$dopparams['Glaukuchet']->isNewRecord) {
                    echo DynaGrid::widget(Proc::DGopts([
                                'options' => ['id' => 'glprepgrid'],
                                'columns' => Proc::DGcols([
                                    'columns' => [
                                        'idPreparat.preparat_name',
                                    ],
                                    'buttons' => [
                                        'deleteajax' => ['Glauk/glprep/delete', 'glprep_id']
                                    ],
                                ]),
                                'gridOptions' => [
                                    'dataProvider' => $dopparams['dataProviderglprep'],
                                    'filterModel' => $dopparams['searchModelglprep'],
                                    'panel' => [
                                        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-tint"></i> Препараты</h3>',
                                        'before' => Html::a('<i class="glyphicon glyphicon-download"></i> Добавить препарат', ['Base/preparat/forglaukuchet',
                                            'foreignmodel' => 'Glprep',
                                            'url' => $this->context->module->requestedRoute,
                                            'field' => 'id_preparat',
                                            'id' => $model->primaryKey,
                                                ], ['class' => 'btn btn-success', 'data-pjax' => '0']),
                                    ],
                                ]
                    ]));
                }
                ?>

                <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
                    <div class="panel-heading"><?= Html::encode('Снятие с учета') ?></div>
                    <div class="panel-body">
                        <?=
                        $form->field($dopparams['Glaukuchet'], 'glaukuchet_deregdate')->widget(DateControl::classname(), [
                            'type' => DateControl::FORMAT_DATE,
                            'options' => [
                                'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                            ],
                            'saveOptions' => ['class' => 'form-control setsession', 'form' => $formname],
                        ])
                        ?>
                        <?=
                        $form->field($dopparams['Glaukuchet'], 'glaukuchet_deregreason')->widget(Select2::classname(), [
                            'hideSearch' => true,
                            'data' => [1 => 'Смерть', 2 => 'Миграция', 3 => 'Другое'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                            'options' => ['placeholder' => 'Выберете причину снятия с учета', 'class' => 'form-control setsession', 'form' => $formname],
                            'theme' => Select2::THEME_BOOTSTRAP,
                        ]);
                        ?>
                    </div>
                </div>

                <?=
                $form->field($dopparams['Glaukuchet'], 'glaukuchet_comment')->textarea([
                    'class' => 'form-control setsession',
                    'form' => $formname, 'maxlength' => 512,
                    'placeholder' => 'Введите комментарий к карте глаукомного пациента',
                    'rows' => 10,
                ]);
                ?>

            </div>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Назад', Proc::GetPreviousURLBreadcrumbsFromSession(), ['class' => 'btn btn-info']) ?>
                <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => $formname]) ?>
            </div>
        </div>
    </div>

</div>
