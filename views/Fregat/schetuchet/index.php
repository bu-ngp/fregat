<?php

use app\func\Proc;
use kartik\dynagrid\DynaGrid;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\SchetuchetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Счета учета';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="schetuchet-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    echo DynaGrid::widget(Proc::DGopts([
        'options' => ['id' => 'schetcuhetgrid'],
        'columns' => Proc::DGcols([
            'columns' => [
                'schetuchet_kod',
                'schetuchet_name',
            ],
            'buttons' => array_merge(
                empty($foreign) ? [] : [
                    'chooseajax' => ['Fregat/schetuchet/assign-to-select2']
                ], Yii::$app->user->can('SchetuchetEdit') ? [
                'update' => ['Fregat/schetuchet/update'],
                'deleteajax' => ['Fregat/schetuchet/delete'],
            ] : []
            ),
        ]),
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'heading' => '<i class="glyphicon glyphicon-folder-open"></i> ' . $this->title,
                'before' => Yii::$app->user->can('SchetuchetEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
            ],
        ]
    ]));
    ?>
</div>
