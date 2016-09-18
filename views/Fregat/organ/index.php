<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\OrganSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Организации';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="organ-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    echo DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'organgrid'],
                'columns' => Proc::DGcols([
                    'columns' => [
                        'organ_name',
                        'organ_email',
                        'organ_phones',
                    ],
                    'buttons' => array_merge(
                            empty($foreign) ? [] : [
                                'chooseajax' => ['Fregat/organ/assign-to-osmotrakt']
                                    ], Yii::$app->user->can('OrganEdit') ? [
                                'update' => ['Fregat/organ/update', 'organ_id'],
                                'deleteajax' => ['Fregat/organ/delete', 'organ_id'],
                                    ] : []
                    ),
                ]),
                'gridOptions' => [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'panel' => [
                        'heading' => '<i class="glyphicon glyphicon-briefcase"></i> ' . $this->title,
                        'before' => Yii::$app->user->can('OrganEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
                    ],
                ]
    ]));
    ?>

</div>
