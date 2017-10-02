<?php

use app\models\Fregat\Material;
use app\models\Fregat\Schetuchet;
use kartik\file\FileInput;
use yii\bootstrap\Tabs;
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
    $material_tip = $model::VariablesValues('material_tip');
    if ($model->isNewRecord) {
        unset($material_tip[1]);
        unset($material_tip[2]);
    }
    ?>

    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Материальная ценность') ?></div>
        <div class="panel-body">

            <?= $form->field($model, 'material_id')->hiddenInput()->label(false) ?>

            <?= $form->field($model, 'material_name')->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => $disabled]) ?>
            <div class="col-md-12" style="padding-left: 0;">
                <div class="col-md-4" style="padding-left: 0;">
                    <?=
                    $form->field($model, 'material_tip')->widget(Select2::classname(), [
                        'hideSearch' => true,
                        'data' => $material_tip,
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
                        'onlyAjax' => false,
                    ]));
                    ?>

                    <?php
                    $material_inv_disabled = $model->material_tip == Material::V_KOMPLEKTE ?: $disabled;

                    echo $form->field($model, 'material_inv', ['enableClientValidation' => false])
                        ->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => $material_inv_disabled]);
                    ?>

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
                        'onlyAjax' => false,
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

                </div>
                <div
                    style="border: 3px solid #cccccc; height: 600px; font-size: 400px; text-align: center; color: #e7e7e7; padding-top: 5px;"
                    class="col-md-8">
                    <div style="position: absolute; left: 50%">
                        <i class="glyphicon glyphicon-camera" style="position: relative; left: -50%;"></i>
                    </div>

                    <?=
                    \metalguardian\fotorama\Fotorama::widget([
                        'items' => $gallery,
                        'id' => 'material_fotorama',
                        'options' => [
                            'nav' => 'thumbs',
                            'minheight' => 520,
                            'maxheight' => 520,
                            'width' => '100%',
                        ]
                    ])
                    ?>
                </div>
            </div>
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
                    'showresultfields' => ['schetuchet_kod', 'schetuchet_name'],
                ],
                'placeholder' => 'Выберете счет учета',
                'resultrequest' => 'Fregat/schetuchet/selectinput',
                'thisroute' => $this->context->module->requestedRoute,
                'fromgridroute' => 'Fregat/schetuchet/index',
                'methodquery' => 'selectinput',
                'onlyAjax' => false,
            ]));
            ?>

            <?=
            $form->field($model, 'material_comment')->textarea([
                'class' => 'form-control setsession',
                'maxlength' => 512,
                'rows' => 10,
                'style' => 'resize: none',
            ])
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

                <?=
                Tabs::widget([
                    'items' => [
                        [
                            'label' => 'Движение',
                            'content' => $this->render('_mattraffic_jurnal', [
                                'dataProvider_mattraffic' => $dataProvider_mattraffic,
                                'searchModel_mattraffic' => $searchModel_mattraffic,
                                'model' => $model,
                            ]),
                        ],
                        [
                            'label' => 'Состав',
                            'content' => $this->render('_material_contain', [
                                'dataProvider_mattraffic_contain' => $dataProvider_mattraffic_contain,
                                'searchModel_mattraffic_contain' => $searchModel_mattraffic_contain,
                                'model' => $model,
                            ]),
                        ],
                        [
                            'label' => 'Осмотр',
                            'items' => array_merge(
                                [
                                    [
                                        'label' => 'Как основное средство',
                                        'content' => $this->render('_osmotr_jurnal', [
                                            'dataProvider_recovery' => $dataProvider_recovery,
                                            'searchModel_recovery' => $searchModel_recovery,
                                            'model' => $model,
                                        ]),
                                    ],
                                ],
                                in_array($model->material_tip, [Material::MATERIAL, Material::MATERIAL_R]) ?
                                    [[
                                        'label' => 'Как материал',
                                        'content' => $this->render('_osmotrmat_jurnal', [
                                            'dataProvider_recoverymat' => $dataProvider_recoverymat,
                                            'searchModel_recoverymat' => $searchModel_recoverymat,
                                            'model' => $model,
                                        ]),
                                    ]] : []
                            ),
                        ],
                        [
                            'label' => 'Восстановление',
                            'items' => array_merge(
                                [
                                    [
                                        'label' => 'Как основное средство',
                                        'content' => $this->render('_recovery_jurnal', [
                                            'dataProvider_recoverysend' => $dataProvider_recoverysend,
                                            'searchModel_recoverysend' => $searchModel_recoverysend,
                                            'model' => $model,
                                        ]),
                                    ]
                                ],
                                in_array($model->material_tip, [Material::MATERIAL, Material::MATERIAL_R]) ?
                                    [[
                                        'label' => 'Как материал',
                                        'content' => $this->render('_recoverymat_jurnal', [
                                            'dataProvider_recoverysendmat' => $dataProvider_recoverysendmat,
                                            'searchModel_recoverysendmat' => $searchModel_recoverysendmat,
                                            'model' => $model,
                                        ]),
                                    ]] : []
                            ),
                        ],
                        [
                            'label' => 'Списание',
                            'items' => array_merge(
                                [
                                    [
                                        'label' => 'Как основное средство',
                                        'content' => $this->render('_spisosnovakt', [
                                            'searchModel_spisosnovakt' => $searchModel_spisosnovakt,
                                            'dataProvider_spisosnovakt' => $dataProvider_spisosnovakt,
                                            'model' => $model,
                                        ]),
                                    ]],
                                in_array($model->material_tip, [Material::MATERIAL, Material::MATERIAL_R]) ?
                                    [[
                                        'label' => 'Как материал',
                                        'content' => $this->render('_spismat', [
                                            'searchModel_spismat' => $searchModel_spismat,
                                            'dataProvider_spismat' => $dataProvider_spismat,
                                            'model' => $model,
                                        ])
                                    ]] : []
                            ),
                        ],
                        [
                            'label' => 'Требования-накладные',
                            'content' => $this->render('_material_naklad', [
                                'searchModel_naklad' => $searchModel_naklad,
                                'dataProvider_naklad' => $dataProvider_naklad,
                                'model' => $model,
                            ]),
                        ],
                    ],
                ]);
                ?>

            </div>
        </div>

        <?=
        DynaGrid::widget(Proc::DGopts([
            'options' => ['id' => 'materialDocfilesgrid'],
            'columns' => Proc::DGcols([
                'columns' => [
                    [
                        'attribute' => 'idDocfiles.docfiles_ext',
                        'format' => 'raw',
                        'value' => 'idDocfiles.docfiles_iconshow',
                        'contentOptions' => ['style' => 'width: 40px; text-align: center;'], // <-- right here
                        'filter' => false,
                    ],
                    [
                        'attribute' => 'idDocfiles.docfiles_name',
                        'format' => 'raw',
                        'value' => 'idDocfiles.docfiles_name_html',
                    ],
                    [
                        'attribute' => 'idDocfiles.docfiles_hash',
                        'visible' => false,
                    ],
                ],
                'buttons' => array_merge(Yii::$app->user->can('DocfilesEdit') ? [
                    //   'deleteajax' => ['Fregat/material-docfiles/delete', 'material_docfiles_id', 'materialDocfilesgrid'],
                    'deleteajax_custom' => function ($url, $model) use ($Params) {
                        $customurl = Yii::$app->getUrlManager()->createUrl(['Fregat/material-docfiles/delete', 'id' => $model->primaryKey]);
                        return Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                            'type' => 'button',
                            'title' => 'Удалить',
                            'class' => 'btn btn-xs btn-danger',
                            'onclick' => 'ConfirmDeleteDialogToAjax("Вы уверены, что хотите удалить запись?", "' . $customurl . '", "materialDocfilesgrid", {}' . (in_array($model->idDocfiles->docfiles_ext, ['PNG', 'JPG', 'JPEG']) ? ', function(obj) { LoadImages(); }' : '') . ' )'
                        ]);
                    },
                ] : []
                ),
            ]),
            'gridOptions' => [
                'dataProvider' => $dataProvidermd,
                'filterModel' => $searchModelmd,
                'panel' => [
                    'heading' => '<i class="glyphicon glyphicon-file"></i> Прикрепленные файлы',
                    'before' => (Yii::$app->user->can('MaterialEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить из справочника прикрепленных файлов', ['Fregat/docfiles/index',
                        'foreignmodel' => 'MaterialDocfiles',
                        'url' => $this->context->module->requestedRoute,
                        'field' => 'id_docfiles',
                        'id' => $model->primaryKey,
                    ], ['class' => 'btn btn-success', 'data-pjax' => '0']) : ''),
                ],
            ]
        ])); ?>

        <?php
        $form2 = ActiveForm::begin([
            'id' => 'UploadDocform',
        ]);
        ?>

        <?= $form2->field($UploadFile, 'docFile')->widget(FileInput::classname(), [
            'pluginOptions' => [
                'uploadUrl' => Url::to(['Fregat/material-docfiles/create']),
                'uploadExtraData' => [
                    'id_material' => $_GET['id'],
                ],
                'dropZoneEnabled' => false,
                'previewZoomSettings' => [
                    'image' => [
                        'width' => 'auto',
                        'height' => '100%',
                    ],
                ],
                'showPreview' => false,
                'showUpload' => false,
                'showCancel' => false,
            ],
            'pluginEvents' => [
                "change" => 'function(event) { $("#uploaddocfile-docfile").fileinput("upload"); }',
                "fileuploaded" => 'function(event, data, previewId, index) { UploadedFiles("materialDocfilesgrid", event, data); LoadImages(data); }'
            ],
        ]);
        ?>

        <?php ActiveForm::end(); ?>

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
