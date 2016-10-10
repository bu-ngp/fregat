<?php

use app\func\Proc;
use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\DocfilesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Загруженные файлы';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="docfiles-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    echo DynaGrid::widget(Proc::DGopts([
        'options' => ['id' => 'docfilesgrid'],
        'columns' => Proc::DGcols([
            'columns' => [
                'docfiles_ext',
                'docfiles_name',
                [
                    'attribute' => 'docfiles_hash',
                    'visible' => false,
                ],
            ],
            'buttons' => array_merge(
                empty($foreign) ? [] : [
                    'chooseajax' => ['Fregat/docfiles/assign-to-select2']
                ], Yii::$app->user->can('DocfilesEdit') ? [
                'update' => ['Fregat/docfiles/update'],
                'deleteajax' => ['Fregat/docfiles/delete'],
            ] : []
            ),
        ]),
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'heading' => '<i class="glyphicon glyphicon-file"></i> ' . $this->title,
                'before' => Yii::$app->user->can('BuildEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
            ],
        ]
    ])); ?>
</div>
