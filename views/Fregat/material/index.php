<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\ButtonGroup;
use app\models\Fregat\Material;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\MaterialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Журнал материальных ценностей';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="material-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';
    $material_tip = Material::VariablesValues('material_tip');
    $material_writeoff = Material::VariablesValues('material_writeoff');
    $material_importdo = Material::VariablesValues('material_importdo');

    echo DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'materialgrid'],
                'columns' => Proc::DGcols([
                    'buttonsfirst' => true,
                    'columns' => [
                        [
                            'attribute' => 'material_tip',
                            'filter' => $material_tip,
                            'value' => function ($model) use ($material_tip) {
                                return isset($material_tip[$model->material_tip]) ? $material_tip[$model->material_tip] : '';
                            },
                        ],
                        'idMatv.matvid_name',
                        'material_name',
                        'material_inv',
                        'material_number',
                        'idIzmer.izmer_name',
                        'material_price',
                        [
                            'attribute' => 'material_serial',
                            'visible' => false,
                        ],
                        [
                            'attribute' => 'material_release',
                            'format' => 'date',
                            'visible' => false,
                        ],
                        [
                            'attribute' => 'material_writeoff',
                            'filter' => $material_writeoff,
                            'value' => function ($model) use ($material_writeoff) {
                                return isset($material_writeoff[$model->material_writeoff]) ? $material_writeoff[$model->material_writeoff] : '';
                            },
                            'visible' => false,
                        ],
                        [
                            'attribute' => 'material_username',
                            'visible' => false,
                        ],
                        [
                            'attribute' => 'material_lastchange',
                            'format' => 'datetime',
                            'visible' => false,
                        ],
                        [
                            'attribute' => 'material_importdo',
                            'filter' => $material_importdo,
                            'value' => function ($model) use ($material_importdo) {
                                return isset($material_importdo[$model->material_importdo]) ? $material_importdo[$model->material_importdo] : '';
                            },
                            'visible' => false,
                        ],
                    ],
                    'buttons' => array_merge(
                            empty($foreign) ? [] : [
                                'chooseajax' => ['Fregat/material/assign-material']
                                    ], /* Yii::$app->user->can('MaterialEdit') */ true ? [
                                'karta' => function ($url, $model) {
                                    $customurl = Yii::$app->getUrlManager()->createUrl(['Fregat/material/update', 'id' => $model->material_id]);
                                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-pencil"></i>', $customurl, ['title' => 'Карта материальной ценности', 'class' => 'btn btn-xs btn-warning', 'data-pjax' => '0']);
                                }
                                            //'delete' => ['Fregat/material/delete', 'material_id'],
                                            ] : []
                            ),
                        ]),
                        'gridOptions' => [
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'panel' => [
                                'heading' => '<i class="glyphicon glyphicon-picture"></i> ' . $this->title,
                                'before' =>
                                ButtonGroup::widget([
                                    'buttons' => [
                                        true/* Yii::$app->user->can('EmployeeEdit') */ ?
                                                ButtonDropdown::widget([
                                                    'label' => '<i class="glyphicon glyphicon-plus"></i> Приход',
                                                    'encodeLabel' => false,
                                                    'dropdown' => [
                                                        'encodeLabels' => false,
                                                        'items' => [
                                                            ['label' => 'Составить акт прихода материальнной ценности <i class="glyphicon glyphicon-plus-sign"></i>', 'url' => Url::to(['Fregat/material/create']), 'options' => ['data-pjax' => '0']],
                                                        ],
                                                    ],
                                                    'options' => ['class' => 'btn btn-success']
                                                ]) : [],
                                    ]
                                ]),
                            ],
                        ]
            ]));
            ?>

        </div>
        <div class="form-group">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Назад', Proc::GetPreviousURLBreadcrumbsFromSession(), ['class' => 'btn btn-info']) ?>
        </div>
    </div>
</div>