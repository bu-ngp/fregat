<?php
use app\func\Proc;
use kartik\dynagrid\DynaGrid;
use yii\bootstrap\Html;
use yii\helpers\Url;

echo DynaGrid::widget(Proc::DGopts([
    'options' => ['id' => 'recoverygrid'],
    'columns' => Proc::DGcols([
        'columns' => [
            'osmotrakt_id',
            [
                'attribute' => 'osmotrakt_date',
                'format' => 'date',
            ],
            'idReason.reason_text',
            'osmotrakt_comment',
            [
                'attribute' => 'idUser.idperson.auth_user_fullname',
                'label' => 'ФИО пользоателя',
            ],
            [
                'attribute' => 'idUser.iddolzh.dolzh_name',
                'label' => 'Должность пользоателя',
            ],
            [
                'attribute' => 'idUser.idbuild.build_name',
                'label' => 'Здание пользоателя',
            ],
            [
                'attribute' => 'idMaster.idperson.auth_user_fullname',
                'label' => 'ФИО мастера',
            ],
            [
                'attribute' => 'idMaster.iddolzh.dolzh_name',
                'label' => 'Должность мастера',
            ],
        ],
        'buttons' => array_merge(
            [
                'osmotraktreport' => function ($url, $model) use ($params) {
                    return Html::button('<i class="glyphicon glyphicon-list"></i>', [
                        'type' => 'button',
                        'title' => 'Скачать акт осмотра матер-ой цен-ти',
                        'class' => 'btn btn-xs btn-default',
                        'onclick' => 'DownloadReport("' . Url::to(['Fregat/osmotrakt/osmotrakt-report']) . '", null, {id: ' . $model->primaryKey . '} )'
                    ]);
                }
            ],
            Yii::$app->user->can('OsmotraktEdit') ? [
                'osmotraktview' => function ($url, $model) use ($params) {
                    $customurl = Yii::$app->getUrlManager()->createUrl(['Fregat/osmotrakt/update', 'id' => $model->primaryKey]);
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $customurl, ['title' => 'Открыть', 'class' => 'btn btn-xs btn-success', 'data-pjax' => '0']);
                },
            ] : [])
    ]),
    'gridOptions' => [
        'dataProvider' => $dataProvider_recovery,
        'filterModel' => $searchModel_recovery,
        'panel' => [
            'heading' => '<i class="glyphicon glyphicon-search"></i> Осмотр, как основная материальная ценность',
        ],
    ]
]));