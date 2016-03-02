<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\Import\LogreportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

/*   'logreport_id',
  'logreport_date',
  'logreport_errors',
  'logreport_updates',
  'logreport_additions',
  'logreport_amount',
  'logreport_missed', */

$this->title = 'Отчеты импорта из 1С';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="logreport-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    echo DynaGrid::widget(Proc::DGopts([
                'columns' => Proc::DGcols([
                    'columns' => [
                        'logreport_id',
                        [
                            'attribute' => 'logreport_executetime',
                            'value' => function ($model, $key, $index, $column) {
                                return date("H:i:s", strtotime($model->logreport_executetime));
                            }
                        ],
                        [
                            'attribute' => 'logreport_date',
                            'value' => function ($model, $key, $index, $column) {
                                return date("d.m.Y", strtotime($model->logreport_date));
                            }
                        ],
                    /*    [
                            'attribute' => 'maxfilelastdate',
                            'value' => function ($model, $key, $index, $column) {
                                return date("d.m.Y H:i:s", strtotime($model->maxfilelastdate));
                            }
                        ],*/
                        'logreport_errors',
                        'logreport_updates',
                        'logreport_additions',
                        'logreport_amount',
                        'logreport_missed',
                    ],
                    'buttons' =>
                    [
                        'download' => function ($url, $model, $key) {
                            return \yii\helpers\Html::a('<i class="glyphicon glyphicon-download-alt"></i>', 'importreports/Отчет импорта в систему Фрегат N' . $model['logreport_id'] . '.xlsx', ['title' => 'Скачать отчет', 'class' => 'btn btn-xs btn-info', 'data-pjax' => '0']);
                        },
                            ],
                        ]),
                        'gridOptions' => [
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'options' => ['id' => 'logreportgrid'],
                            'panel' => [
                                'heading' => '<i class="glyphicon glyphicon-inbox"></i> ' . $this->title,
                                'before' => Html::a('<i class="glyphicon glyphicon-flash"></i> Очистить отчеты', ['Fregat/logreport/clear'], ['class' => 'btn btn-danger'/* , 'data-pjax' => '0' */, 'data' => [
                                        'confirm' => "Вы уверены, что хотите очистить все отчеты?",
                                        'method' => 'post',
                            ]]),
                            ],
                        ]
            ]));
            ?>

</div>
