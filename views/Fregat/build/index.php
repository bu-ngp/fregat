<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\BuildSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Здания';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="build-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    echo DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'buildgrid'],
                'columns' => Proc::DGcols([
                    'buttonsfirst' => true,
                    'columns' => [
                        'build_name',
                    ],
                    'buttons' => array_merge(
                            empty($foreign) ? [] : [
                                'chooseajax' => ['Fregat/build/assign-to-employee']
                                    ], Yii::$app->user->can('BuildEdit') ? [
                                'update' => ['Fregat/build/update', 'build_id'],
                                'deleteajax' => ['Fregat/build/delete', 'build_id'],
                                    ] : []
                    ),
                ]),
                'gridOptions' => [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'panel' => [
                        'heading' => '<i class="glyphicon glyphicon-home"></i> ' . $this->title,
                        'before' => Yii::$app->user->can('BuildEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
                    ],
                ]
    ]));
    ?>

</div>
