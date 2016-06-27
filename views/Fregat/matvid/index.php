<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\MatvidSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Виды материальных ценностей';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="matvid-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    echo DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'matvidgrid'],
                'columns' => Proc::DGcols([
                    'columns' => [
                        'matvid_name',
                    ],
                    'buttons' => array_merge(
                            empty($foreign) ? [] : [
                                'chooseajax' => ['Fregat/matvid/assign-to-material']
                                ], Yii::$app->user->can('MatvidEdit') ? [
                                        'update' => ['Fregat/matvid/update', 'matvid_id'],
                                        'deleteajax' => ['Fregat/matvid/delete', 'matvid_id'],
                                            ] : []
                            ),
                        ]),
                        'gridOptions' => [
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'panel' => [
                                'heading' => '<i class="glyphicon glyphicon-credit-card"></i> ' . $this->title,
                                'before' => Yii::$app->user->can('MatvidEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
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