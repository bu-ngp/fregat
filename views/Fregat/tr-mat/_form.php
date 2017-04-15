<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use app\func\Proc;
use kartik\touchspin\TouchSpin;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\TrMat */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tr-mat-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    /* $form->field($model, 'id_parent')->widget(Select2::classname(), Proc::DGselect2([
                 'model' => $model,
                 'resultmodel' => new app\models\Fregat\Material,
                 'fields' => [
                     'keyfield' => 'id_parent',
                 ],
                 'placeholder' => 'Введите инвентарный номер материальной ценности',
                 'fromgridroute' => 'Fregat/material/forinstallakt_mat',
                 'resultrequest' => 'Fregat/tr-mat/selectinputfortrmatparent',
                 'thisroute' => $this->context->module->requestedRoute,
                 'methodquery' => 'selectinputfortrmat_parent',
                 'methodparams' => ['idinstallakt' => (string) filter_input(INPUT_GET, 'idinstallakt')],
                 'dopparams' => [
                     'idinstallakt' => (string) filter_input(INPUT_GET, 'idinstallakt'),
                 ],
     ]));*/
    ?>

    <?=
    $form->field($model, 'id_parent')->widget(Select2::classname(), array_merge(Proc::DGselect2([
        'model' => $model,
        'resultmodel' => new app\models\Fregat\Mattraffic,
        'fields' => [
            'keyfield' => 'id_parent',
        ],
        'placeholder' => 'Введите инвентарный номер материальной ценности',
        'fromgridroute' => 'Fregat/mattraffic/forinstallakt_matparent',
        'resultrequest' => 'Fregat/tr-mat/selectinputfortrmatparent',
        'thisroute' => $this->context->module->requestedRoute,
        'methodquery' => 'selectinputfortrmat_parent',
        'methodparams' => ['idinstallakt' => (string)filter_input(INPUT_GET, 'idinstallakt')],
        'dopparams' => [
            'idinstallakt' => (string)filter_input(INPUT_GET, 'idinstallakt'),
        ],
    ]),
        [
            'pluginEvents' => [
                "select2:select" => "function() { SetParentMaterial(); }",
                "select2:unselect" => "function() { UnSetParentMaterial(); }"
            ],
        ]));
    ?>

    <?=
    $form->field($model, 'id_mattraffic')->widget(Select2::classname(), array_merge(Proc::DGselect2([
            'model' => $model,
            'resultmodel' => new app\models\Fregat\Mattraffic,
            'fields' => [
                'keyfield' => 'id_mattraffic',
            ],
            'placeholder' => 'Введите инвентарный номер материальной ценности',
            'fromgridroute' => 'Fregat/mattraffic/forinstallakt_mat',
            'resultrequest' => 'Fregat/tr-mat/selectinputfortrmatchild',
            'thisroute' => $this->context->module->requestedRoute,
            'methodquery' => 'selectinputfortrmat_child',
            'methodparams' => /*array_merge(*/
                [
                    'idinstallakt' => (string)filter_input(INPUT_GET, 'idinstallakt'),
                    'id_parent' => ['forInit' => $model->id_parent, 'forJs' => '$("#trmat-id_parent").val()'],
                ],// empty($model->id_parent) ? [] : ['id_parent' => $model->id_parent]),
            'dopparams' => array_merge([
                'foreigndo' => '1',
                'idinstallakt' => (string)filter_input(INPUT_GET, 'idinstallakt'),

            ], empty($model->id_parent) ? [] : ['id_parent' => $model->id_parent]),
            'disabled' => empty($model->id_parent),
        ]),
            [
                'pluginEvents' => [
                    "select2:select" => "function() { SetMaxNumberByMaterial(); }",
                    "select2:unselect" => "function() { UnSetMaxNumberByMaterial(); }"
                ],
            ]
        )
    )->label('Перемещаемая материальная ценность');
    ?>

    <?=
    $form->field($Mattraffic, 'mattraffic_number', [
        'inputTemplate' => '<div class="input-group">{input}<span id="mattraffic_number_max" class="input-group-addon">' . $mattraffic_number_max . '</span></div>'
    ])->widget(TouchSpin::classname(), [
        'options' => ['class' => 'form-control setsession'],
        'pluginOptions' => [
            'verticalbuttons' => true,
            'min' => 0.001,
            'max' => 10000000000,
            'step' => 1,
            'decimals' => 3,
            'forcestepdivisibility' => 'none',
        ]
    ]);
    ?>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">

                <?= Html::submitButton('<i class="glyphicon glyphicon-plus"></i> Добавить', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
