<?php

use kartik\dynagrid\DynaGrid;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Build */
/* @var $form yii\widgets\ActiveForm */
/* @var $searchModel app\models\Fregat\CabinetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<div class="build-form">

    <?php
    $form = ActiveForm::begin([
        'id' => 'Buildform',
    ]);
    ?>

    <?= $form->field($model, 'build_name')->textInput(['maxlength' => true, 'class' => 'form-control setsession inputuppercase', 'autofocus' => true]) ?>

    <?php ActiveForm::end(); ?>

    <?php
    if (!$model->isNewRecord) {
        echo DynaGrid::widget(Proc::DGopts([
            'options' => ['id' => 'buildcabinetsgrid'],
            'columns' => Proc::DGcols([
                'columns' => [
                    'cabinet_name',
                ],
                'buttons' => [
                        'update' => ['Fregat/cabinet/update'],
                    'deleteajax' => ['Fregat/cabinet/delete'],
                ],
            ]),
            'gridOptions' => [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-modal-window"></i> Кабинеты</h3>',
                    'before' => Html::a('<i class="glyphicon glyphicon-download"></i> Добавить кабинет', ['Fregat/cabinet/create',
                        'idbuild' => $model->primaryKey,
                    ], ['class' => 'btn btn-success', 'data-pjax' => '0']),
                ],
            ]
        ]));
    }
    ?>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Buildform']) ?>
            </div>
        </div>
    </div>

</div>
