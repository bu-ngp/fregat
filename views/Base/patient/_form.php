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
    ?>

    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Паспорт пациента') ?></div>
        <div class="panel-body">

            <?= $form->field($model, 'patient_fam')->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'autofocus' => true]) ?>

            <?= $form->field($model, 'patient_im')->textInput(['maxlength' => true, 'class' => 'form-control setsession']) ?>

            <?= $form->field($model, 'patient_ot')->textInput(['maxlength' => true, 'class' => 'form-control setsession']) ?>

            <?=
            $form->field($model, 'patient_dr')->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
                'options' => [
                    'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
                ],
            ])
            ?>

            <?=
            $form->field($model, 'patient_pol')->widget(Select2::classname(), [
                'hideSearch' => true,
                'data' => [1 => 'Мужской', 2 => 'Женский'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'options' => ['placeholder' => 'Выберете пол пациента', 'class' => 'form-control setsession'],
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
                                'disabled' => $Fias->AOGUID === NULL,
                                'resultrequest' => 'Base/fias/selectinputforstreet',
                                'thisroute' => $this->context->module->requestedRoute,
                                'methodquery' => 'selectinputforstreet',
                                'ajaxparams' => ['fias_city' => '$(\'select[name="Fias[AOGUID]"]\').val()'],
                                'minimuminputlength' => 2,
                            ]), [
                            /*  'pluginEvents' => [
                              "select2:select" => "function() { FillTrOsnov(); }",
                              "select2:unselect" => "function() { ClearTrOsnov(); }"
                              ], */
            ]))
            ?>

            <?php
            /*  echo $form->field($model, 'id_mattraffic')->widget(Select2::classname(), array_merge(Proc::DGselect2([
              'model' => $model,
              'resultmodel' => new app\models\Fregat\Mattraffic,
              'fields' => [
              'keyfield' => 'id_mattraffic',
              ],
              'placeholder' => 'Введите инвентарный номер материальной ценности',
              'fromgridroute' => 'Fregat/mattraffic/forinstallakt',
              'resultrequest' => 'Fregat/tr-osnov/selectinputfortrosnov',
              'thisroute' => $this->context->module->requestedRoute,
              'methodquery' => 'selectinputfortrosnov',
              'dopparams' => [
              'foreigndo' => '1',
              'idinstallakt' => (string) filter_input(INPUT_GET, 'idinstallakt'),
              ],
              ]), [
              'pluginEvents' => [
              "select2:select" => "function() { FillTrOsnov(); }",
              "select2:unselect" => "function() { ClearTrOsnov(); }"
              ],
              ])); */
            ?>

            <?= $form->field($model, 'patient_dom')->textInput(['maxlength' => true, 'class' => 'form-control setsession']) ?>

            <?= $form->field($model, 'patient_korp')->textInput(['maxlength' => true, 'class' => 'form-control setsession']) ?>

            <?= $form->field($model, 'patient_kvartira')->textInput(['maxlength' => true, 'class' => 'form-control setsession']) ?>

        </div>
    </div>

    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Карта глаукомного пациента') ?></div>
        <div class="panel-body">
            <?=
            $form->field($Glaukuchet, 'glaukuchet_uchetbegin')->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
                'options' => [
                    'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
                ],
            ])
            ?>

            <?=
            $form->field($Glaukuchet, 'glaukuchet_lastvisit')->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
                'options' => [
                    'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
                ],
            ])
            ?>

            <!-- Врач -->

            <?=
            $form->field($Glaukuchet, 'glaukuchet_detect')->widget(Select2::classname(), [
                'hideSearch' => true,
                'data' => [1 => 'При обращении за лечением', 2 => 'При обращении по диспансеризации'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'options' => ['placeholder' => 'Выберете вид выявления заболевания', 'class' => 'form-control setsession'],
                'theme' => Select2::THEME_BOOTSTRAP,
            ]);
            ?>

            <?=
            $form->field($Glaukuchet, 'glaukuchet_stage')->widget(Select2::classname(), [
                'hideSearch' => true,
                'data' => [1 => 'I стадия', 2 => 'II стадия', 3 => 'III стадия', 4 => 'IV стадия'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'options' => ['placeholder' => 'Выберете вид выявления заболевания', 'class' => 'form-control setsession'],
                'theme' => Select2::THEME_BOOTSTRAP,
            ]);
            ?>

            <?=
            $form->field($Glaukuchet, 'glaukuchet_operdate')->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
                'options' => [
                    'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
                ],
            ])
            ?>

            <?=
            $form->field($Glaukuchet, 'glaukuchet_invalid')->widget(Select2::classname(), [
                'hideSearch' => true,
                'data' => [1 => 'I группа', 2 => 'II группа', 3 => 'III группа'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'options' => ['placeholder' => 'Выберете вид выявления заболевания', 'class' => 'form-control setsession'],
                'theme' => Select2::THEME_BOOTSTRAP,
            ]);
            ?>

            <?=
            $form->field($Glaukuchet, 'glaukuchet_lastmetabol')->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
                'options' => [
                    'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
                ],
            ])
            ?>

            <?=
            $form->field($Glaukuchet, 'glaukuchet_rlocat')->widget(Select2::classname(), [
                'hideSearch' => true,
                'data' => [1 => 'Федеральная', 2 => 'Региональная'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'options' => ['placeholder' => 'Выберете вид выявления заболевания', 'class' => 'form-control setsession'],
                'theme' => Select2::THEME_BOOTSTRAP,
            ]);
            ?>

            <!-- Препараты -->

            <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
                <div class="panel-heading"><?= Html::encode('Снятие с учета') ?></div>
                <div class="panel-body">
                    <?=
                    $form->field($Glaukuchet, 'glaukuchet_deregdate')->widget(DateControl::classname(), [
                        'type' => DateControl::FORMAT_DATE,
                        'options' => [
                            'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
                        ],
                    ])
                    ?>
                    <?=
                    $form->field($Glaukuchet, 'glaukuchet_deregreason')->widget(Select2::classname(), [
                        'hideSearch' => true,
                        'data' => [1 => 'Смерть', 2 => 'Миграция', 3 => 'Другое'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'options' => ['placeholder' => 'Выберете причину снятия с учета', 'class' => 'form-control setsession'],
                        'theme' => Select2::THEME_BOOTSTRAP,
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Patientform']) ?>
            </div>
        </div>
    </div>

</div>
