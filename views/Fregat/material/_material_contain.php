<?php

use app\func\Proc;
use kartik\dynagrid\DynaGrid;
use yii\bootstrap\Html;
use yii\helpers\Url;

echo DynaGrid::widget(Proc::DGopts([
    'options' => ['id' => 'mattraffic_contain_grid'],
    'columns' => Proc::DGcols([
        'columns' => [
            'id_installakt',
            [
                'attribute' => 'idInstallakt.installakt_date',
                'format' => 'date',
            ],
            [
                'attribute' => 'idMattraffic.idMaterial.material_name',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<a data-pjax="0" href="' . Url::to(['Fregat/material/update', 'id' => $model->idMattraffic->idMaterial->primaryKey]) . '">' . $model->idMattraffic->idMaterial->material_name . '</a>';
                }
            ],
            'idMattraffic.idMaterial.material_inv',
            'idMattraffic.mattraffic_number',
            [
                'attribute' => 'idMattraffic.idMol.idperson.auth_user_fullname',
                'label' => 'Материально-ответственное лицо',
            ],
            [
                'attribute' => 'idMattraffic.idMol.iddolzh.dolzh_name',
                'label' => 'Должность материально-ответственного лица',
            ],
            [
                'attribute' => 'idMattraffic.idMol.idbuild.build_name',
                'label' => 'Здание материально-ответственного лица',
            ],
            [
                'attribute' => 'idMattraffic.mattraffic_username',
                'visible' => false,
            ],
            [
                'attribute' => 'idMattraffic.mattraffic_lastchange',
                'format' => 'datetime',
                'visible' => false,
            ],
        ],
        'buttons' => array_merge(['installaktmatreport' => function ($url, $model) {
            return Html::button('<i class="glyphicon glyphicon-list"></i>', [
                'type' => 'button',
                'title' => 'Скачать акт установки матер-ой цен-ти',
                'class' => 'btn btn-xs btn-default',
                'onclick' => 'DownloadReport("' . Url::to(['Fregat/installakt/installakt-report']) . '", null, {id: ' . $model->id_installakt . '} )'
            ]);
        },
        ],
            Yii::$app->user->can('InstallEdit') ? [
                'update' => ['Fregat/installakt/update', 'id_installakt'],
            ] : []),
    ]),
    'gridOptions' => [
        'dataProvider' => $dataProvider_mattraffic_contain,
        'filterModel' => $searchModel_mattraffic_contain,
        'panel' => [
            'heading' => '<i class="glyphicon glyphicon-th-list"></i> Состав материальной ценности',
        ],
    ]
]));