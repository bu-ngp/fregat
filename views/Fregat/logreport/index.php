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
    <?=
    DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'logreportgrid'],
                'columns' => Proc::DGcols([
                    'columns' => [
                        'logreport_id',
                        [
                            'attribute' => 'logreport_executetime',
                            'format' => 'time',
                        ],
                        [
                            'attribute' => 'logreport_memoryused',
                            'value' => function ($model, $key, $index, $column) {
                                Yii::$app->formatter->sizeFormatBase = 1000;
                                return Yii::$app->formatter->asShortSize($model->logreport_memoryused);
                            }
                        ],
                        [
                            'attribute' => 'logreport_date',
                            'format' => 'date',
                        ],
                        'logreport_errors',
                        'logreport_updates',
                        'logreport_additions',
                        'logreport_amount',
                        'logreport_missed',
                    ],
                    'buttons' =>
                    [
                        'download' => function ($url, $model) {
                            return \yii\helpers\Html::a('<i class="glyphicon glyphicon-download-alt"></i>', 'importreports/Отчет импорта в систему Фрегат N' . $model['logreport_id'] . '.xlsx', ['title' => 'Скачать отчет', 'class' => 'btn btn-xs btn-info', 'data-pjax' => '0']);
                        },
                            ],
                        ]),
                        'gridOptions' => [
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'panel' => [
                                'heading' => '<i class="glyphicon glyphicon-inbox"></i> ' . $this->title,
                                'before' => Html::button('<i class="glyphicon glyphicon-trash"></i> Очистить отчеты', [
                                    'type' => 'button',
                                    'title' => 'Удалить',
                                    'class' => 'btn btn-danger',
                                    'onclick' => 'ConfirmDeleteDialogToAjax("Вы уверены, что хотите очистить все отчеты?", "' . Yii::$app->getUrlManager()->createUrl(['Fregat/logreport/clear']) . '")'
                                ]),
                            ],
                        ]
            ]));
            ?>

        </div>
        