<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use app\models\Fregat\Organ;
use app\models\Fregat\Recoveryrecieveakt;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Recoverysendakt */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recoverysendakt-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'Recoverysendaktform',
    ]);
    ?>

    <?=
    $form->field($model, 'recoverysendakt_date')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE,
        'options' => [
            'options' => [ 'placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
        ],
    ])
    ?>

    <?=
    $form->field($model, 'id_organ')->widget(Select2::classname(), Proc::DGselect2([
                'model' => $model,
                'resultmodel' => new Organ,
                'fields' => [
                    'keyfield' => 'id_organ',
                    'resultfield' => 'organ_name',
                ],
                'placeholder' => 'Выберете организацию',
                'resultrequest' => 'Fregat/organ/selectinput',
                'thisroute' => $this->context->module->requestedRoute,
                'fromgridroute' => 'Fregat/organ/index',
    ]));
    ?>

    <?php ActiveForm::end(); ?>

    <?php
    if (!$model->isNewRecord) {
        echo $form->field(new app\models\Fregat\Osmotrakt, 'osmotrakt_id')->widget(Select2::classname(), [
            'options' => ['placeholder' => 'Введите инвентарный номер материальной ценности', 'class' => 'form-control'],
            'theme' => Select2::THEME_BOOTSTRAP,
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => 'Fregat/osmotrakt/selectinput',
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term} }'),
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            ],
            'addon' => [
                'append' => [
                    'content' => Html::a('<i class="glyphicon glyphicon-arrow-down"></i>  Вставить в таблицу', '#', ['class' => 'btn btn-success', 'id' => 'addrecoveryrecieveakt']),
                    'asButton' => true
                ]
            ],
        ])->label('Для быстрого добавления материальных ценностей');

        $recoveryrecieveakt_repaired = Recoveryrecieveakt::VariablesValues('recoveryrecieveakt_repaired');
        echo DynaGrid::widget(Proc::DGopts([
                    'options' => ['id' => 'recoveryrecieveaktgrid'],
                    'columns' => Proc::DGcols([
                        'buttonsfirst' => true,
                        'columns' => [
                            'idOsmotrakt.idTrosnov.idMattraffic.idMaterial.material_inv',
                            'idOsmotrakt.idTrosnov.idMattraffic.idMaterial.material_name',
                            'idOsmotrakt.idTrosnov.idMattraffic.idMol.idbuild.build_name',
                            'idOsmotrakt.idTrosnov.tr_osnov_kab',
                            'idOsmotrakt.idReason.reason_text',
                            'idOsmotrakt.osmotrakt_comment',
                            [
                                'attribute' => 'idOsmotrakt.idMaster.idperson.auth_user_fullname',
                                'label' => 'ФИО составителя акта осмотра',
                            ],
                            [
                                'attribute' => 'idOsmotrakt.osmotrakt_date',
                                'format' => 'date',
                                'visible' => false,
                            ],
                            [
                                'attribute' => 'idOsmotrakt.idMaster.iddolzh.dolzh_name',
                                'label' => 'Должность составителя акта осмотра',
                                'visible' => false,
                            ],
                            'recoveryrecieveakt_result',
                            [
                                'attribute' => 'recoveryrecieveakt_repaired',
                                'filter' => $recoveryrecieveakt_repaired,
                                'value' => function ($model) use ($recoveryrecieveakt_repaired) {
                                    return isset($recoveryrecieveakt_repaired[$model->recoveryrecieveakt_repaired]) ? $recoveryrecieveakt_repaired[$model->recoveryrecieveakt_repaired] : '';
                                },
                            ],
                            [
                                'attribute' => 'recoveryrecieveakt_date',
                                'format' => 'date',
                            ],
                        ],
                        'buttons' => [
                            'deleteajax' => ['Fregat/recoveryrecieveakt/delete'],
                        ],
                    ]),
                    'gridOptions' => [
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'panel' => [
                            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-compressed"></i> Восстанавливаемые материальные ценности</h3>',
                            'before' => Html::a('<i class="glyphicon glyphicon-download"></i> Добавить материальную ценность', ['Fregat/osmotrakt/index',
                                'foreignmodel' => 'Recoveryrecieveakt',
                                'url' => $this->context->module->requestedRoute,
                                'field' => 'id_osmotrakt',
                                'id' => $model->primaryKey,
                                    ], ['class' => 'btn btn-success', 'data-pjax' => '0']),
                        ],
                    ]
        ]));
    }
    ?>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Назад', Proc::GetPreviousURLBreadcrumbsFromSession(), ['class' => 'btn btn-info']) ?>
                <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Recoverysendaktform']) ?>
            </div>
        </div> 
    </div>
</div>
