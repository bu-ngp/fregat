<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\IzmerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Единицы измерения';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="izmer-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    echo DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'izmergrid'],
                'columns' => Proc::DGcols([
                    'columns' => [
                        'izmer_name',
                    ],
                    'buttons' => array_merge(
                            empty($foreign) ? [] : [
                                'chooseajax' => ['Fregat/izmer/assign-to-material']
                                    ], Yii::$app->user->can('IzmerEdit') ? [
                                'update' => ['Fregat/izmer/update', 'izmer_id'],
                                'deleteajax' => ['Fregat/izmer/delete', 'izmer_id'],
                                    ] : []
                    ),
                ]),
                'gridOptions' => [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'panel' => [
                        'heading' => '<i class="glyphicon glyphicon-pushpin"></i> ' . $this->title,
                        'before' => Yii::$app->user->can('IzmerEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
                    ],
                ]
    ]));
    ?>

</div>
