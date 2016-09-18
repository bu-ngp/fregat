<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\OsmotraktmatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Журнал осмотров материалов';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="osmotraktmat-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    echo DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'osmotraktmatgrid'],
                'columns' => Proc::DGcols([
                    'columns' => [
                        'osmotraktmat_id',
                        [
                            'attribute' => 'osmotraktmat_date',
                            'format' => 'date',
                        ],
                        [
                            'attribute' => 'idMaster.idperson.auth_user_fullname',
                            'label' => 'ФИО составителя акта',
                        ],
                        [
                            'attribute' => 'idMaster.iddolzh.dolzh_name',
                            'visible' => false,
                            'label' => 'Должность составителя акта',
                        ],
                        [
                            'attribute' => 'osmotraktmat_countmat',
                        ],
                    ],
                    'buttons' => array_merge(
                            empty($foreign) ? [
                                'downloadreport' => ['Fregat/osmotraktmat/osmotraktmat-report']] : [
                                'chooseajax' => ['Fregat/osmotrakt/assign-to-recoveryrecieveakt']], Yii::$app->user->can('OsmotraktEdit') ? [
                                'update' => ['Fregat/osmotraktmat/update'],
                                'deleteajax' => ['Fregat/osmotraktmat/delete'],
                                    ] : []
                    ),
                ]),
                'gridOptions' => [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'panel' => [
                        'heading' => '<i class="glyphicon glyphicon-search"></i> ' . $this->title,
                        'before' => Yii::$app->user->can('OsmotraktEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
                    ],
                ]
    ]));
    ?>
</div>
