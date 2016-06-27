<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\ReasonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Шаблоны актов осмотра материальной ценности';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reason-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    echo DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'reasongrid'],
                'columns' => Proc::DGcols([
                    'columns' => [
                        'reason_text',
                    ],
                    'buttons' => array_merge(
                            empty($foreign) ? [] : [
                                'chooseajax' => ['Fregat/reason/assign-to-osmotrakt']
                                    ], Yii::$app->user->can('ReasonEdit') ? [
                                'update' => ['Fregat/reason/update', 'reason_id'],
                                'deleteajax' => ['Fregat/reason/delete', 'reason_id'],
                                    ] : []
                    ),
                ]),
                'gridOptions' => [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'panel' => [
                        'heading' => '<i class="glyphicon glyphicon-briefcase"></i> ' . $this->title,
                        'before' => Yii::$app->user->can('ReasonEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
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