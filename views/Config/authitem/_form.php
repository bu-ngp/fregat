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
        'data' => [1 => 'Роль', 2 => 'Операция'],
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
        $session = new Session;
        $session->open();

        echo DynaGrid::widget(Proc::DGopts([
                    'columns' => Proc::DGcols([
                        'columns' => [
                            'children.description',
                            [
                                'attribute' => 'children.type',
                                'filter' => [1 => 'Роль', 2 => 'Операция'],
                                'value' => function ($model) {
                            return $model->children->type == 1 ? 'Роль' : 'Операция';
                        },
                            ],
                            'children.name',
                        ],
                        'buttons' => [
                            'deletecustom' => function ($url, $model) {
                                $customurl = Yii::$app->getUrlManager()->createUrl(['Config/authitemchild/delete', 'parent' => $model->parent, 'child' => $model->child]);
                                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-trash"></i>', $customurl, ['title' => 'Удалить'/* , 'data-pjax' => '0' */, 'class' => 'btn btn-xs btn-danger', 'data' => [
                                                'confirm' => "Вы уверены, что хотите удалить запись?",
                                                'method' => 'post',
                                ]]);
                            }
                                ],
                            ]),
                            'gridOptions' => [
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'options' => ['id' => 'authitemchildgrid'],
                                'panel' => [
                                    'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-paperclip"></i> Дочерние авторизационные единицы</h3>',
                                    'before' => Html::a('<i class="glyphicon glyphicon-download"></i> Добавить дочернюю авторизационную единицу', ['Config/authitem/forauthitemchild',
                                        'foreignmodel' => 'Authitemchild', //substr($model->className(), strrpos($model->className(), '\\') + 1),
                                        'url' => $this->context->module->requestedRoute,
                                        'field' => 'child',
                                        'id' => $model->primaryKey,
                                            // 'id' => $_GET['id'],
                                            ], ['class' => 'btn btn-success', 'data-pjax' => '0']),
                                ],
                            ]
                ]));

                $session->close();
            }
            ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Authitemform']) ?>
        </div>
    </div>

</div>

