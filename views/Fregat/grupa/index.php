<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\GrupaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Группы материальных ценностей';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="grupa-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    echo DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'grupagrid'],
                'columns' => Proc::DGcols([
                    'columns' => [
                        'grupa_name',
                    ],
                    'buttons' => array_merge(
                            empty($foreign) ? [] : [
                                'chooseajax' => ['Fregat/grupa/assign-to-material']
                                ], Yii::$app->user->can('GrupaEdit') ? [
                                        'update' => ['Fregat/grupa/update', 'grupa_id'],
                                        'deleteajax' => ['Fregat/grupa/delete', 'grupa_id']] : []
                            ),
                        ]),
                        'gridOptions' => [
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'panel' => [
                                'heading' => '<i class="glyphicon glyphicon-duplicate"></i> ' . $this->title,
                                'before' => Yii::$app->user->can('GrupaEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
                            ],
                        ]
            ]));
            ?>

        </div>
        