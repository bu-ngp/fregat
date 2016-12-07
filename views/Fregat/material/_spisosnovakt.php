<?php
use app\func\Proc;
use app\models\Fregat\Recoveryrecieveakt;
use kartik\dynagrid\DynaGrid;
use yii\bootstrap\Html;
use yii\helpers\Url;

echo DynaGrid::widget(Proc::DGopts([
    'options' => ['id' => 'spisosnovakt_material_grid'],
    'columns' => Proc::DGcols([
        'columns' => [
            'idSpisosnovakt.spisosnovakt_id',
            [
                'attribute' => 'idSpisosnovakt.spisosnovakt_date',
                'format' => 'date',
            ],
            'idSpisosnovakt.idMol.idperson.auth_user_fullname',
            [
                'attribute' =>  'idSpisosnovakt.idMol.idperson.auth_user_fullname',
                'label' => 'ФИО материально-ответственного лица',
            ],
            [
                'attribute' =>  'idSpisosnovakt.idMol.iddolzh.dolzh_name',
                'label' => 'Должность материально-ответственного лица',
            ],
            [
                'attribute' =>  'idSpisosnovakt.idMol.idpodraz.podraz_name',
                'label' => 'Подразделение материально-ответственного лица',
            ],
            [
                'attribute' =>  'idSpisosnovakt.idMol.idbuild.build_name',
                'label' => 'Здание материально-ответственного лица',
            ],
            'spisosnovmaterials_number',
        ],
        'buttons' => [
            'spisosnovaktreport' => function ($url, $model) use ($params) {
                return Html::button('<i class="glyphicon glyphicon-list"></i>', [
                    'type' => 'button',
                    'title' => 'Скачать заявку на списание основных средств',
                    'class' => 'btn btn-xs btn-default',
                    'onclick' => 'DownloadReport("' . Url::to(['Fregat/spisosnovakt/spisosnovakt-report']) . '", null, {id: ' . $model->id_spisosnovakt . '} )'
                ]);
            },
        ],
    ]),
    'gridOptions' => [
        'dataProvider' => $dataProvider_spisosnovakt,
        'filterModel' => $searchModel_spisosnovakt,
        'panel' => [
            'heading' => '<i class="glyphicon glyphicon-paste"></i> Списание, как основная материальная ценность',
        ],
    ]
]));