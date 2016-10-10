<?php

use app\models\Fregat\Schetuchet;
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

<div class="material-form">

    <?php
    $form = ActiveForm::begin([
        'id' => 'Materialform',
    ]);

    $disabled = !Yii::$app->user->can('MaterialEdit');
    ?>

    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Материальная ценность') ?></div>
        <div class="panel-body">

            <?=
            $form->field($model, 'material_tip')->widget(Select2::classname(), [
                'hideSearch' => true,
                'data' => $model::VariablesValues('material_tip'),
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'options' => ['placeholder' => 'Выберете тип материальной ценности', 'class' => 'form-control setsession'],
                'theme' => Select2::THEME_BOOTSTRAP,
                'disabled' => ($model->isNewRecord) ? $disabled : true,
                'pluginEvents' => [
                    "select2:select" => "function() { SetTipMaterial(); }",
                    "select2:unselect" => "function() { UnsetTipMaterial(); }",
                ],
            ]);
            ?>

            <?=
            $form->field($model, 'id_matvid')->widget(Select2::classname(), Proc::DGselect2([
                'model' => $model,
                'resultmodel' => new Matvid,
                'fields' => [
                    'keyfield' => 'id_matvid',
                    'resultfield' => 'matvid_name',
                ],
                'placeholder' => 'Выберете вид материальной ценности',
                'fromgridroute' => 'Fregat/matvid/index',
                'resultrequest' => 'Fregat/matvid/selectinput',
                'thisroute' => $this->context->module->requestedRoute,
                'disabled' => $disabled,
            ]));
            ?>

            <?= $form->field($model, 'material_name')->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => $disabled]) ?>

            <?= $form->field($model, 'material_inv')->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => $disabled]) ?>

            <?=
            $form->field($model, 'material_number')->widget(kartik\touchspin\TouchSpin::classname(), [
                'options' => ['class' => 'form-control setsession'],
                'pluginOptions' => [
                    'verticalbuttons' => true,
                    'min' => 1,
                    'max' => 10000000000,
                    'step' => 1,
                    'decimals' => 3,
                    'forcestepdivisibility' => 'none',
                ],
                'disabled' => ($model->isNewRecord) ? $disabled : true,
            ]);
            ?>

            <?=
            $form->field($model, 'id_izmer')->widget(Select2::classname(), Proc::DGselect2([
                'model' => $model,
                'resultmodel' => new Izmer,
                'fields' => [
                    'keyfield' => 'id_izmer',
                    'resultfield' => 'izmer_name',
                ],
                'placeholder' => 'Выберете единицу измерения',
                'fromgridroute' => 'Fregat/izmer/index',
                'resultrequest' => 'Fregat/izmer/selectinput',
                'thisroute' => $this->context->module->requestedRoute,
                'disabled' => $disabled,
            ]));
            ?>

            <?=
            $form->field($model, 'material_price')->widget(kartik\touchspin\TouchSpin::classname(), [
                'options' => ['class' => 'form-control setsession'],
                'pluginOptions' => [
                    'verticalbuttons' => true,
                    'min' => 0,
                    'max' => 1000000000,
                    'step' => 1,
                    'decimals' => 2,
                    'forcestepdivisibility' => 'none',
                ],
                'disabled' => $disabled,
            ]);
            ?>

            <?= $form->field($model, 'material_serial')->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => $disabled]) ?>

            <?=
            $form->field($model, 'material_release')->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
                'options' => [
                    'options' => ['placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
                ],
                'disabled' => $disabled,
            ])
            ?>

            <?php
            if (!$model->isNewRecord)
                echo $form->field($model, 'material_writeoff')->widget(Select2::classname(), [
                    'hideSearch' => true,
                    'data' => $model::VariablesValues('material_writeoff'),
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'options' => ['placeholder' => 'Выберете списан ли материал', 'class' => 'form-control setsession'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'disabled' => true,
                ]);
            ?>

            <?=
            $form->field($model, 'id_schetuchet')->widget(Select2::classname(), Proc::DGselect2([
                'model' => $model,
                'resultmodel' => new Schetuchet,
                'fields' => [
                    'keyfield' => 'id_schetuchet',
                    'resultfield' => 'schetuchet_kod',
                ],
                'placeholder' => 'Выберете счет учета',
                'resultrequest' => 'Fregat/schetuchet/selectinput',
                'thisroute' => $this->context->module->requestedRoute,
                'fromgridroute' => 'Fregat/schetuchet/index',
                'methodquery' => 'selectinput',
            ]));
            ?>

            <?=
            $form->field($model, 'material_importdo')->checkbox(['disabled' => $disabled]);
            ?>

        </div>
    </div>
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Приход') ?></div>
        <div class="panel-body">

            <?=
            $form->field($Mattraffic, 'id_mol')->widget(Select2::classname(), Proc::DGselect2([
                'model' => $Mattraffic,
                'resultmodel' => new app\models\Fregat\Employee,
                'fields' => [
                    'keyfield' => 'id_mol',
                    'resultfield' => 'idperson.auth_user_fullname',
                ],
                'placeholder' => 'Выберете материально отчетственное лицо',
                'fromgridroute' => 'Fregat/employee/index',
                'resultrequest' => 'Fregat/employee/selectinputemloyee',
                'thisroute' => $this->context->module->requestedRoute,
                'methodquery' => 'selectinput',
                'disabled' => $disabled,
            ]));
            ?>

            <?=
            $form->field($Mattraffic, 'mattraffic_date')->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
                'options' => [
                    'options' => ['placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
                ],
                'disabled' => $disabled,
            ])
            ?>

        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if (!$model->isNewRecord): ?>
        <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
            <div class="panel-heading"><?= Html::encode('Движение материальной ценности') ?></div>
            <div class="panel-body">
                <?php
                $mattraffic_tip = Mattraffic::VariablesValues('mattraffic_tip');
                echo DynaGrid::widget(Proc::DGopts([
                    'options' => ['id' => 'mattraffic_karta_grid'],
                    'columns' => Proc::DGcols([
                        'columns' => [
                            [
                                'attribute' => 'mattraffic_id',
                                'visible' => false,
                            ],
                            [
                                'attribute' => 'mattraffic_date',
                                'format' => 'date',
                            ],
                            [
                                'attribute' => 'mattraffic_tip',
                                'filter' => $mattraffic_tip,
                                'value' => function ($model) use ($mattraffic_tip) {
                                    return isset($mattraffic_tip[$model->mattraffic_tip]) ? $mattraffic_tip[$model->mattraffic_tip] : '';
                                },
                            ],
                            'mattraffic_number',
                            [
                                'attribute' => 'idMol.idperson.auth_user_fullname',
                                'label' => 'Материально-ответственное лицо',
                            ],
                            [
                                'attribute' => 'idMol.iddolzh.dolzh_name',
                                'label' => 'Должность материально-ответственного лица',
                            ],
                            [
                                'attribute' => 'idMol.idbuild.build_name',
                                'label' => 'Здание материально-ответственного лица',
                            ],
                            [
                                'attribute' => 'mattraffic_username',
                                'visible' => false,
                            ],
                            [
                                'attribute' => 'mattraffic_lastchange',
                                'format' => 'datetime',
                                'visible' => false,
                            ],
                            [
                                'attribute' => 'trOsnovs.tr_osnov_kab',
                                'value' => function ($model) {
                                    return $model->trOsnovs[0]->tr_osnov_kab;
                                },
                            ],
                            [
                                'attribute' => 'trMats.idParent.idMaterial.material_inv',
                                'label' => 'Инвент-ый номер мат-ой цен-ти, в которую включен в состав',
                                'value' => function ($model) {
                                    return $model->trMats[0]->idParent->idMaterial->material_inv;
                                },
                            ],
                        ],
                        'buttons' => array_merge(['installaktreport' => function ($url, $model) {
                            if ($model->mattraffic_tip == 3)
                                $idinstallakt = $model->trOsnovs[0]->id_installakt;
                            elseif ($model->mattraffic_tip == 4)
                                $idinstallakt = $model->trMats[0]->id_installakt;

                            if ($model->mattraffic_tip == 3 || $model->mattraffic_tip == 4)
                                return Html::button('<i class="glyphicon glyphicon-list"></i>', [
                                    'type' => 'button',
                                    'title' => 'Скачать акт перемещения матер-ой цен-ти',
                                    'class' => 'btn btn-xs btn-default',
                                    'onclick' => 'DownloadReport("' . Url::to(['Fregat/installakt/installakt-report']) . '", null, {id: ' . $idinstallakt . '} )'
                                ]);
                            else
                                return '';
                        },
                        ], Yii::$app->user->can('MaterialMolDelete') ? [
                            'deletemol' => function ($url, $model) use ($params) {
                                $customurl = Yii::$app->getUrlManager()->createUrl(['Fregat/mattraffic/delete', 'id' => $model->primaryKey]);
                                return (in_array($model->mattraffic_tip, [1, 2])) ? Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                                    'type' => 'button',
                                    'title' => 'Удалить',
                                    'class' => 'btn btn-xs btn-danger',
                                    'onclick' => 'ConfirmDeleteDialogToAjax("Вы уверены, что хотите удалить запись?", "' . $customurl . '", "mattraffic_karta_grid")'
                                ]) : '';
                            },
                        ] : []),
                    ]),
                    'gridOptions' => [
                        'dataProvider' => $dataProvider_mattraffic,
                        'filterModel' => $searchModel_mattraffic,
                        'panel' => [
                            'heading' => '<i class="glyphicon glyphicon-random"></i> Движение материальной ценности',
                            'before' => Yii::$app->user->can('MolEdit') ? Html::a('<i class="glyphicon glyphicon-education"></i> Сменить Материально-ответственное лицо', ['Fregat/mattraffic/create',
                                'id' => $model->primaryKey,
                            ], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
                        ],
                    ]
                ]));

                echo DynaGrid::widget(Proc::DGopts([
                    'options' => ['id' => 'mattraffic_contain_grid'],
                    'columns' => Proc::DGcols([
                        'columns' => [
                            'id_installakt',
                            [
                                'attribute' => 'idInstallakt.installakt_date',
                                'format' => 'date',
                            ],
                            'idMattraffic.idMaterial.material_name',
                            'idMattraffic.idMaterial.material_inv',
                            'idMattraffic.mattraffic_number',
                            [
                                'attribute' => 'idMattraffic.idMol.idperson.auth_user_fullname',
                                'label' => 'Материально-ответственное лицо',
                            ],
                            [
                                'attribute' => 'idMattraffic.idMol.iddolzh.dolzh_name',
                                'label' => 'Должность материально-ответственного лица',
                            ],
                            [
                                'attribute' => 'idMattraffic.idMol.idbuild.build_name',
                                'label' => 'Здание материально-ответственного лица',
                            ],
                            [
                                'attribute' => 'idMattraffic.mattraffic_username',
                                'visible' => false,
                            ],
                            [
                                'attribute' => 'idMattraffic.mattraffic_lastchange',
                                'format' => 'datetime',
                                'visible' => false,
                            ],
                        ],
                        'buttons' => array_merge(['installaktmatreport' => function ($url, $model) {
                            return Html::button('<i class="glyphicon glyphicon-list"></i>', [
                                'type' => 'button',
                                'title' => 'Скачать акт перемещения матер-ой цен-ти',
                                'class' => 'btn btn-xs btn-default',
                                'onclick' => 'DownloadReport("' . Url::to(['Fregat/installakt/installakt-report']) . '", null, {id: ' . $model->id_installakt . '} )'
                            ]);
                        },
                        ]),
                    ]),
                    'gridOptions' => [
                        'dataProvider' => $dataProvider_mattraffic_contain,
                        'filterModel' => $searchModel_mattraffic_contain,
                        'panel' => [
                            'heading' => '<i class="glyphicon glyphicon-th-list"></i> Состав материальной ценности',
                        ],
                    ]
                ]));
                ?>
            </div>
        </div>
        <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
            <div class="panel-heading"><?= Html::encode('Восстановление материальной ценности') ?></div>
            <div class="panel-body">
                <?php
                echo DynaGrid::widget(Proc::DGopts([
                    'options' => ['id' => 'recoverygrid'],
                    'columns' => Proc::DGcols([
                        'columns' => [
                            'osmotrakt_id',
                            [
                                'attribute' => 'osmotrakt_date',
                                'format' => 'date',
                            ],
                            'idReason.reason_text',
                            'osmotrakt_comment',
                            [
                                'attribute' => 'idUser.idperson.auth_user_fullname',
                                'label' => 'ФИО пользоателя',
                            ],
                            [
                                'attribute' => 'idUser.iddolzh.dolzh_name',
                                'label' => 'Должность пользоателя',
                            ],
                            [
                                'attribute' => 'idUser.idbuild.build_name',
                                'label' => 'Здание пользоателя',
                            ],
                            [
                                'attribute' => 'idMaster.idperson.auth_user_fullname',
                                'label' => 'ФИО мастера',
                            ],
                            [
                                'attribute' => 'idMaster.iddolzh.dolzh_name',
                                'label' => 'Должность мастера',
                            ],
                        ],
                        'buttons' => [
                            'osmotraktreport' => function ($url, $model) use ($params) {
                                return Html::button('<i class="glyphicon glyphicon-list"></i>', [
                                    'type' => 'button',
                                    'title' => 'Скачать акт осмотра матер-ой цен-ти',
                                    'class' => 'btn btn-xs btn-default',
                                    'onclick' => 'DownloadReport("' . Url::to(['Fregat/osmotrakt/osmotrakt-report']) . '", null, {id: ' . $model->primaryKey . '} )'
                                ]);
                            },
                        ],
                    ]),
                    'gridOptions' => [
                        'dataProvider' => $dataProvider_recovery,
                        'filterModel' => $searchModel_recovery,
                        'panel' => [
                            'heading' => '<i class="glyphicon glyphicon-search"></i> Осмотр, как основная материальная ценность',
                        ],
                    ]
                ]));
                echo DynaGrid::widget(Proc::DGopts([
                    'options' => ['id' => 'recoverymatgrid'],
                    'columns' => Proc::DGcols([
                        'columns' => [
                            'idOsmotraktmat.osmotraktmat_id',
                            [
                                'attribute' => 'idOsmotraktmat.osmotraktmat_date',
                                'format' => 'date',
                            ],
                            'tr_mat_osmotr_number',
                            'idReason.reason_text',
                            'tr_mat_osmotr_comment',
                            'idOsmotraktmat.idMaster.idperson.auth_user_fullname',
                            'idOsmotraktmat.idMaster.iddolzh.dolzh_name',
                        ],
                        'buttons' => [
                            'osmotraktmatreport' => function ($url, $model) use ($params) {
                                return Html::button('<i class="glyphicon glyphicon-list"></i>', [
                                    'type' => 'button',
                                    'title' => 'Скачать акт осмотра материала',
                                    'class' => 'btn btn-xs btn-default',
                                    'onclick' => 'DownloadReport("' . Url::to(['Fregat/osmotraktmat/osmotraktmat-report']) . '", null, {id: ' . $model->id_osmotraktmat . '} )'
                                ]);
                            },
                        ],
                    ]),
                    'gridOptions' => [
                        'dataProvider' => $dataProvider_recoverymat,
                        'filterModel' => $searchModel_recoverymat,
                        'panel' => [
                            'heading' => '<i class="glyphicon glyphicon-search"></i> Осмотр, как материал',
                        ],
                    ]
                ]));

                $recoveryrecieveakt_repaired = Recoveryrecieveakt::VariablesValues('recoveryrecieveakt_repaired');
                echo DynaGrid::widget(Proc::DGopts([
                    'options' => ['id' => 'recoverysend_grid'],
                    'columns' => Proc::DGcols([
                        'columns' => [
                            'id_recoverysendakt',
                            [
                                'attribute' => 'idRecoverysendakt.recoverysendakt_date',
                                'format' => 'date',
                            ],
                            [
                                'attribute' => 'recoveryrecieveakt_date',
                                'format' => 'date',
                            ],
                            'recoveryrecieveakt_result',
                            [
                                'attribute' => 'recoveryrecieveakt_repaired',
                                'filter' => $recoveryrecieveakt_repaired,
                                'value' => function ($model) use ($recoveryrecieveakt_repaired) {
                                    return isset($recoveryrecieveakt_repaired[$model->recoveryrecieveakt_repaired]) ? $recoveryrecieveakt_repaired[$model->recoveryrecieveakt_repaired] : '';
                                },
                            ],
                            'id_osmotrakt',
                        ],
                        'buttons' => [
                            'recoveryrecieveaktreport' => function ($url, $model) use ($params) {
                                return Html::button('<i class="glyphicon glyphicon-list"></i>', [
                                    'type' => 'button',
                                    'title' => 'Скачать акт получения матер-ных цен-тей от сторонней организации',
                                    'class' => 'btn btn-xs btn-default',
                                    'onclick' => 'DownloadReport("' . Url::to(['Fregat/recoveryrecieveakt/recoveryrecieveakt-report']) . '", null, {id: ' . $model->id_recoverysendakt . '} )'
                                ]);
                            },
                        ],
                    ]),
                    'gridOptions' => [
                        'dataProvider' => $dataProvider_recoverysend,
                        'filterModel' => $searchModel_recoverysend,
                        'panel' => [
                            'heading' => '<i class="glyphicon glyphicon-wrench"></i> Восстановление, как основная материальная ценность',
                        ],
                    ]
                ]));

                $recoveryrecieveaktmat_repaired = Recoveryrecieveaktmat::VariablesValues('recoveryrecieveaktmat_repaired');
                echo DynaGrid::widget(Proc::DGopts([
                    'options' => ['id' => 'recoverysendmat_grid'],
                    'columns' => Proc::DGcols([
                        'columns' => [
                            'id_recoverysendakt',
                            [
                                'attribute' => 'idRecoverysendakt.recoverysendakt_date',
                                'format' => 'date',
                            ],
                            [
                                'attribute' => 'recoveryrecieveaktmat_date',
                                'format' => 'date',
                            ],
                            'recoveryrecieveaktmat_result',
                            [
                                'attribute' => 'recoveryrecieveaktmat_repaired',
                                'filter' => $recoveryrecieveaktmat_repaired,
                                'value' => function ($model) use ($recoveryrecieveaktmat_repaired) {
                                    return isset($recoveryrecieveaktmat_repaired[$model->recoveryrecieveaktmat_repaired]) ? $recoveryrecieveaktmat_repaired[$model->recoveryrecieveaktmat_repaired] : '';
                                },
                            ],
                            'idTrMatOsmotr.id_osmotraktmat',
                        ],
                        'buttons' => [
                            'recoveryrecieveaktmatreport' => function ($url, $model) use ($params) {
                                return Html::button('<i class="glyphicon glyphicon-list"></i>', [
                                    'type' => 'button',
                                    'title' => 'Скачать акт получения материалов от сторонней организации',
                                    'class' => 'btn btn-xs btn-default',
                                    'onclick' => 'DownloadReport("' . Url::to(['Fregat/recoveryrecieveaktmat/recoveryrecieveaktmat-report']) . '", null, {id: ' . $model->id_recoverysendakt . '} )'
                                ]);
                            },
                        ],
                    ]),
                    'gridOptions' => [
                        'dataProvider' => $dataProvider_recoverysendmat,
                        'filterModel' => $searchModel_recoverysendmat,
                        'panel' => [
                            'heading' => '<i class="glyphicon glyphicon-wrench"></i> Восстановление, как материал',
                        ],
                    ]
                ]));
                ?>
            </div>
        </div>

    <?php endif; ?>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                
                <?php
                if (Yii::$app->user->can('MaterialEdit'))
                    echo Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Materialform'])
                ?>
            </div>
        </div>
    </div>

</div>
