<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Dolzh;
use app\models\Podraz;
use app\models\Build;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'employee_fio')->textInput(['maxlength' => true]) ?>

        <?=
    $form->field($model, 'id_dolzh')->widget(Select2::classname(), [
        'initValueText' => empty($model->id_dolzh) ? '' : Dolzh::findOne($model->id_dolzh)->dolzh_name,
        'options' => ['placeholder' => 'Выберете должность'],
        'theme' => Select2::THEME_BOOTSTRAP,
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 1,
            'ajax' => [
                'url' => Url::to(['dolzh/selectinput']),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
        ],
        'addon' => [
            'append' => [
                'content' => Html::a('<i class="glyphicon glyphicon-plus-sign"></i>', ['dolzh/index',
                    'foreignmodel' => substr($model->className(), strrpos($model->className(), '\\') + 1),
                    'url' => $this->context->module->requestedRoute,
                    'field' => 'id_dolzh',
                    'id' => $model->primaryKey,
                        ], ['class' => 'btn btn-success']),
                'asButton' => true
            ]
        ]
    ]);
    ?>

        <?=
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
                    'id' => $model->primaryKey,
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
                    'id' => $model->primaryKey,
                        ], ['class' => 'btn btn-success']),
                'asButton' => true
            ]
        ]
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
