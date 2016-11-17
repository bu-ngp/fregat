<?php
use app\func\Proc;
use kartik\dynagrid\DynaGrid;
use yii\bootstrap\Html;
use yii\helpers\Url;

echo DynaGrid::widget(Proc::DGopts([
    'options' => ['id' => 'recoverymatgrid'],
    'columns' => Proc::DGcols([
        'columns' => [
            'idOsmotraktmat.osmotraktmat_id',
            [
                'attribute' => 'idOsmotraktmat.osmotraktmat_date',
                'format' => 'date',
            ],
            'tr_mat_osmotr_number',
            'idReason.reason_text',
            'tr_mat_osmotr_comment',
            'idOsmotraktmat.idMaster.idperson.auth_user_fullname',
            'idOsmotraktmat.idMaster.iddolzh.dolzh_name',
        ],
        'buttons' => [
            'osmotraktmatreport' => function ($url, $model) use ($params) {
                return Html::button('<i class="glyphicon glyphicon-list"></i>', [
                    'type' => 'button',
                    'title' => 'Скачать акт осмотра материала',
                    'class' => 'btn btn-xs btn-default',
                    'onclick' => 'DownloadReport("' . Url::to(['Fregat/osmotraktmat/osmotraktmat-report']) . '", null, {id: ' . $model->id_osmotraktmat . '} )'
                ]);
            },
        ],
    ]),
    'gridOptions' => [
        'dataProvider' => $dataProvider_recoverymat,
        'filterModel' => $searchModel_recoverymat,
        'panel' => [
            'heading' => '<i class="glyphicon glyphicon-search"></i> Осмотр, как материал',
        ],
    ]
]));