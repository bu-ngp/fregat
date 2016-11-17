<?php
use app\func\Proc;
use app\models\Fregat\Recoveryrecieveakt;
use kartik\dynagrid\DynaGrid;
use yii\bootstrap\Html;
use yii\helpers\Url;

$recoveryrecieveakt_repaired = Recoveryrecieveakt::VariablesValues('recoveryrecieveakt_repaired');
echo DynaGrid::widget(Proc::DGopts([
    'options' => ['id' => 'recoverysend_grid'],
    'columns' => Proc::DGcols([
        'columns' => [
            'id_recoverysendakt',
            [
                'attribute' => 'idRecoverysendakt.recoverysendakt_date',
                'format' => 'date',
            ],
            [
                'attribute' => 'recoveryrecieveakt_date',
                'format' => 'date',
            ],
            'recoveryrecieveakt_result',
            [
                'attribute' => 'recoveryrecieveakt_repaired',
                'filter' => $recoveryrecieveakt_repaired,
                'value' => function ($model) use ($recoveryrecieveakt_repaired) {
                    return isset($recoveryrecieveakt_repaired[$model->recoveryrecieveakt_repaired]) ? $recoveryrecieveakt_repaired[$model->recoveryrecieveakt_repaired] : '';
                },
            ],
            'id_osmotrakt',
        ],
        'buttons' => [
            'recoveryrecieveaktreport' => function ($url, $model) use ($params) {
                return Html::button('<i class="glyphicon glyphicon-list"></i>', [
                    'type' => 'button',
                    'title' => 'Скачать акт получения матер-ных цен-тей от сторонней организации',
                    'class' => 'btn btn-xs btn-default',
                    'onclick' => 'DownloadReport("' . Url::to(['Fregat/recoveryrecieveakt/recoveryrecieveakt-report']) . '", null, {id: ' . $model->id_recoverysendakt . '} )'
                ]);
            },
        ],
    ]),
    'gridOptions' => [
        'dataProvider' => $dataProvider_recoverysend,
        'filterModel' => $searchModel_recoverysend,
        'panel' => [
            'heading' => '<i class="glyphicon glyphicon-wrench"></i> Восстановление, как основная материальная ценность',
        ],
    ]
]));