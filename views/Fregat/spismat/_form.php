<?php

use app\func\Proc;
use app\models\Fregat\Employee;
use app\models\Fregat\Mattraffic;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\datecontrol\DateControl;
use kartik\dynagrid\DynaGrid;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Spismat */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="spismat-form">

    <?php $form = ActiveForm::begin([
        'id' => 'Spismatform',
    ]); ?>


    <?= !$model->isNewRecord ? $form->field($model, 'spismat_id')->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => true]) : '' ?>

    <?=
    $form->field($model, 'spismat_date')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE,
        'options' => [
            'options' => ['placeholder' => 'Выберите дату ...', 'class' => 'form-control setsession'],
        ],
    ])
    ?>

    <?=
    $form->field($model, 'id_mol')->widget(Select2::classname(), Proc::DGselect2([
        'model' => $model,
        'resultmodel' => new Employee,
        'fields' => [
            'keyfield' => 'id_mol',
            'resultfield' => 'idperson.auth_user_fullname',
        ],
        'placeholder' => 'Выберете материально-ответственное лицо',
        'fromgridroute' => 'Fregat/employee/index',
        'resultrequest' => 'Fregat/employee/selectinputemloyee',
        'thisroute' => $this->context->module->requestedRoute,
        'methodquery' => 'selectinput',
        'disabled' => !$model->isNewRecord,
    ]));
    ?>

    <?php ActiveForm::end(); ?>

    <?php
    /*  if (!$model->isNewRecord) {
          echo $form->field(new Mattraffic, 'mattraffic_id')->widget(Select2::classname(), [
              'options' => ['placeholder' => 'Введите инвентарный номер материальной ценности', 'class' => 'form-control'],
              'theme' => Select2::THEME_BOOTSTRAP,
              'pluginOptions' => [
                  'allowClear' => true,
                  'minimumInputLength' => 3,
                  'ajax' => [
                      'url' => Url::to(['Fregat/mattraffic/selectinputforspisosnovakt-fast']),
                      'dataType' => 'json',
                      'data' => new JsExpression('function(params) { return {q:params.term, spisosnovakt_id: ' . $_GET['id'] . '} }'),
                  ],
                  'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
              ],
              'addon' => [
                  'append' => [
                      'content' => Html::button('<i class="glyphicon glyphicon-arrow-down"></i>  Вставить в таблицу', ['class' => 'btn btn-success', 'id' => 'addspisosnovmaterials', 'onclick' => 'AddMattraffic(' . $_GET['id'] . ')']),
                      'asButton' => true
                  ]
              ],
          ])->label('Для быстрого добавления материальных ценностей ( при условии что количество на списание = 1 )');
      }*/
    ?>

    <?php
    if (!$model->isNewRecord) {
        echo DynaGrid::widget(Proc::DGopts([
            'options' => ['id' => 'spismatmaterialsgrid'],
            'columns' => Proc::DGcols([
                'columns' => [
                    [
                        'attribute' => 'idMattraffic.idMaterial.material_name',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a data-pjax="0" href="' . Url::to(['Fregat/material/update', 'id' => $model->idMattraffic->id_material]) . '">' . $model->idMattraffic->idMaterial->material_name . '</a>';
                        }
                    ],
                    'idMattraffic.idMaterial.material_inv',
                    [
                        'attribute' => 'idMattraffic.mattraffic_number',
                        'label' => 'Установленное количество',
                    ],
                    [
                        'attribute' => 'idMattraffic.trMats.idParent.idMaterial.material_name',
                        'label' => 'Наименование, куда установлено',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if (isset($model->idMattraffic->trMats[0]))
                                return '<a data-pjax="0" href="' . Url::to(['Fregat/material/update', 'id' => $model->idMattraffic->trMats[0]->idParent->id_material]) . '">' . $model->idMattraffic->trMats[0]->idParent->idMaterial->material_name . '</a>';
                        },
                    ],
                    [
                        'attribute' => 'idMattraffic.trMats.idParent.idMaterial.material_inv',
                        'label' => 'Инвентарный номер, куда установлено',
                        'value' => function ($model) {
                            if (isset($model->idMattraffic->trMats[0]))
                                return $model->idMattraffic->trMats[0]->idParent->idMaterial->material_inv;
                        },
                    ],
                    [
                        'attribute' => 'idMattraffic.trMats.id_installakt',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if (isset($model->idMattraffic->trMats[0]))
                                return '<a data-pjax="0" href="' . Url::to(['Fregat/installakt/update', 'id' => $model->idMattraffic->trMats[0]->id_installakt]) . '">' . $model->idMattraffic->trMats[0]->id_installakt . '</a>';
                        },
                    ],
                    [
                        'attribute' => 'idMattraffic.trMats.idInstallakt.installakt_date',
                        'format' => 'date',
                        'value' => function ($model) {
                            if (isset($model->idMattraffic->trMats[0]))
                                return $model->idMattraffic->trMats[0]->idInstallakt->installakt_date;
                        },
                    ],
                    [
                        'attribute' => 'idMattraffic.trMats.idInstallakt.idInstaller.idperson.auth_user_fullname',
                        'label' => 'ФИО мастера',
                        'value' => function ($model) {
                            if (isset($model->idMattraffic->trMats[0]))
                                return $model->idMattraffic->trMats[0]->idInstallakt->idInstaller->idperson->auth_user_fullname;
                        },
                    ],
                    [
                        'attribute' => 'idMattraffic.trMats.idInstallakt.idInstaller.iddolzh.dolzh_name',
                        'label' => 'Должность мастера',
                        'value' => function ($model) {
                            if (isset($model->idMattraffic->trMats[0]))
                                return $model->idMattraffic->trMats[0]->idInstallakt->idInstaller->iddolzh->dolzh_name;
                        },
                    ],
                ],
                'buttons' => [
                    'deleteajax' => ['Fregat/spismatmaterials/delete'],
                ],
            ]),
            'gridOptions' => [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-shopping-cart"></i> Списываемые материалы</h3>',
                    'before' => Html::a('<i class="glyphicon glyphicon-download"></i> Добавить материал', ['Fregat/mattraffic/forspismat',
                        'foreignmodel' => 'Spismatmaterials',
                        'url' => $this->context->module->requestedRoute,
                        'field' => 'id_mattraffic',
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
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Spismatform']) ?>
                            <?php
                            if (!$model->isNewRecord) {
                                echo Html::button('<i class="glyphicon glyphicon-list"></i> Скачать ведомость', ['id' => 'DownloadReport', 'class' => 'btn btn-info', 'onclick' => 'DownloadReport("' . Url::to(['Fregat/spismat/spismat-report']) . '", $(this)[0].id, {id: ' . $model->primaryKey . '} )']);
                                echo Html::button('<i class="glyphicon glyphicon-list"></i> Скачать акты установки', ['id' => 'DownloadReport', 'class' => 'btn btn-info', 'onclick' => 'DownloadReport("' . Url::to(['Fregat/spismat/spismat-report']) . '", $(this)[0].id, {id: ' . $model->primaryKey . '} )']);
                                echo '</div></div></div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>