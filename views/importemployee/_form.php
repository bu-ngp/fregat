<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Button;
use app\models\Build;
use app\models\Podraz;
use yii\helpers\ArrayHelper;
//use kartik\widgets\Select2; // or kartik\select2\Select2
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Importemployee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="importemployee-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'importemployee_combination')->textInput(['maxlength' => true]) ?>

    <?=
    /* $form->field($model, 'id_podraz', [
      'template' => '{label}<div class="input-group">{input}<span class="input-group-btn">' . Html::a('<i class="glyphicon glyphicon-plus-sign"></i>', ['podraz/index',
      'foreignmodel' => substr($model->className(), strrpos($model->className(), '\\') + 1),
      'url' => $this->context->module->requestedRoute,
      'field' => 'id_podraz',
      'id' => $model->importemployee_id,
      ], ['class' => 'btn btn-success']) . '</span></div>{hint}{error}',
      ])->dropdownList(Podraz::find()->select(['podraz_name'])->where(['podraz_id' => $model->id_podraz])->indexBy('podraz_id')->column()
      , ['prompt' => 'Выбрать подразделение', 'class' => 'form-control inactive']
      ); */

    $form->field($model, 'id_podraz')->widget(Select2::classname(), [
        'initValueText' => empty($model->id_podraz) ? '' : Podraz::findOne($model->id_podraz)->podraz_name,
        'options' => ['placeholder' => 'Выберете подразделение'],
        'theme' => Select2::THEME_BOOTSTRAP,
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 1,
            'ajax' => [
                'url' => Url::to(['podraz/selectinput']),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
        ],
        'addon' => [
            'append' => [
                'content' => Html::a('<i class="glyphicon glyphicon-plus-sign"></i>', ['podraz/index',
                    'foreignmodel' => substr($model->className(), strrpos($model->className(), '\\') + 1),
                    'url' => $this->context->module->requestedRoute,
                    'field' => 'id_podraz',
                    'id' => $model->importemployee_id,
                        ], ['class' => 'btn btn-success']),
                'asButton' => true
            ]
        ]
    ]);
    ?>

    <?=
    $form->field($model, 'id_build')->widget(Select2::classname(), [
        'initValueText' => empty($model->id_build) ? '' : Build::findOne($model->id_build)->build_name,
        'options' => ['placeholder' => 'Выберете здание'],
        'theme' => Select2::THEME_BOOTSTRAP,
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 1,
            'ajax' => [
                'url' => Url::to(['build/selectinput']),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
        ],
        'addon' => [
            'append' => [
                'content' => Html::a('<i class="glyphicon glyphicon-plus-sign"></i>', ['build/index',
                    'foreignmodel' => substr($model->className(), strrpos($model->className(), '\\') + 1),
                    'url' => $this->context->module->requestedRoute,
                    'field' => 'id_build',
                    'id' => $model->importemployee_id,
                        ], ['class' => 'btn btn-success']),
                'asButton' => true
            ]
        ]
    ]);
    ?>

    <?php
    if (!$model->isNewRecord)
        echo DynaGrid::widget([
            'options' => ['id' => 'dynagrid-1'],
            'showPersonalize' => true,
            'storage' => 'cookie',
            //'allowPageSetting' => false, 
            'allowThemeSetting' => false,
            'allowFilterSetting' => false,
            'allowSortSetting' => false,
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn',
                    'header' => Html::encode('№'),
                ],
                'idemployee.employee_id',
                'idemployee.employee_fio',
                'idemployee.iddolzh.dolzh_name',
                'idemployee.idpodraz.podraz_name',
                'idemployee.idbuild.build_name',
                ['class' => 'kartik\grid\ActionColumn',
                    'header' => Html::encode('Действия'),
                    'template' => '{delete}',
                    'buttons' => [
                                'delete' => function ($url, $model) {
                            $customurl = Yii::$app->getUrlManager()->createUrl(['impemployee/delete', 'id' => $model['impemployee_id']]);
                            return \yii\helpers\Html::a('<i class="glyphicon glyphicon-trash"></i>', $customurl, ['title' => 'Удалить', 'class' => 'btn btn-xs btn-danger', 'data' => [
                                            'confirm' => "Вы уверены, что хотите удалить запись?",
                                            'method' => 'post',
                            ]]);
                        },
                            ],
                            'contentOptions' => ['style' => 'white-space: nowrap;']
                        ],
                    ],
                    'gridOptions' => [
                        'exportConfig' => [
                            GridView::EXCEL => [
                                'label' => 'EXCEL',
                                'filename' => 'EXCEL',
                                'options' => ['title' => 'EXCEL List'],
                            ],
                        ],
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'options' => ['id' => 'impemployeegrid'],
                        'panel' => [ 'type' => GridView::TYPE_DEFAULT,],
                        'toolbar' => [
                            ['content' => '{export} {dynagrid}'],
                        ]
                    ]
                ]);
            ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end();
            ?>

</div>
