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
        'buttons' => array_merge([
            'osmotraktmatreport' => function ($url, $model) use ($params) {
                return Html::button('<i class="glyphicon glyphicon-list"></i>', [
                    'type' => 'button',
                    'title' => 'Скачать акт осмотра материала',
                    'class' => 'btn btn-xs btn-default',
                    'onclick' => 'DownloadReport("' . Url::to(['Fregat/osmotraktmat/osmotraktmat-report']) . '", null, {id: ' . $model->id_osmotraktmat . '} )'
                ]);
            },
        ],
            Yii::$app->user->can('OsmotraktEdit') ? [
                'osmotraktmatview' => function ($url, $model) use ($params) {
                    $customurl = Yii::$app->getUrlManager()->createUrl(['Fregat/osmotraktmat/update', 'id' => $model->id_osmotraktmat]);
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $customurl, ['title' => 'Открыть', 'class' => 'btn btn-xs btn-success', 'data-pjax' => '0']);
                },
            ] : []),
    ]),
    'gridOptions' => [
        'dataProvider' => $dataProvider_recoverymat,
        'filterModel' => $searchModel_recoverymat,
        'panel' => [
            'heading' => '<i class="glyphicon glyphicon-search"></i> Осмотр, как материал',
        ],
    ]
]));