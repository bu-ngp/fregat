<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Fregat\Build;
use app\models\Fregat\Podraz;
use kartik\select2\Select2;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\web\Session;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Importemployee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="authitem-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'Authitemform',
    ]);
    ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'autofocus' => true]) ?>

    <?=
    $form->field($model, 'type')->widget(Select2::classname(), [
        'hideSearch' => true,
        'data' => $model::VariablesValues('type'),
        'options' => ['placeholder' => 'Выберете тип', 'class' => 'form-control setsession'],
        'disabled' => !$model->isNewRecord,
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => !$model->isNewRecord]) ?>

    <?php ActiveForm::end(); ?>

    <?php
    if (!$model->isNewRecord && $model->type == 1) {
        $type = \app\models\Config\Authitem::VariablesValues('type');

        echo DynaGrid::widget(Proc::DGopts([
                    'options' => ['id' => 'authitemchildgrid'],
                    'columns' => Proc::DGcols([
                        'columns' => [
                            'children.description',
                            [
                                'attribute' => 'children.type',
                                'filter' => $type,
                                'value' => function ($model) use ($type) {
                                    return isset($type[$model->children->type]) ? $type[$model->children->type] : '';
                                },
                            ],
                            'children.name',
                        ],
                        'buttons' => [
                            'deletecustom' => function ($url, $model) use ($params) {
                                $customurl = Yii::$app->getUrlManager()->createUrl(['Config/authitemchild/delete', 'parent' => $model->parent, 'child' => $model->child]);
                                return Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                                            'type' => 'button',
                                            'title' => 'Удалить',
                                            'class' => 'btn btn-xs btn-danger',
                                            'onclick' => 'ConfirmDeleteDialogToAjax("Вы уверены, что хотите удалить запись?", "' . $customurl . '")'
                                ]);
                            }],
                            ]),
                            'gridOptions' => [
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'panel' => [
                                    'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-paperclip"></i> Дочерние авторизационные единицы</h3>',
                                    'before' => Html::a('<i class="glyphicon glyphicon-download"></i> Добавить дочернюю авторизационную единицу', ['Config/authitem/forauthitemchild',
                                        'foreignmodel' => 'Authitemchild',
                                        'url' => $this->context->module->requestedRoute,
                                        'field' => 'child',
                                        'id' => $model->primaryKey,
                                            ], ['class' => 'btn btn-success', 'data-pjax' => '0']),
                                ],
                            ]
                ]));
            }
            ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    
                    <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Authitemform']) ?>
        </div>
    </div>

</div>

