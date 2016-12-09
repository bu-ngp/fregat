<?php
use app\func\Proc;
use app\models\Fregat\Recoveryrecieveakt;
use kartik\dynagrid\DynaGrid;
use yii\bootstrap\Html;
use yii\helpers\Url;

echo DynaGrid::widget(Proc::DGopts([
    'options' => ['id' => 'naklad_material_grid'],
    'columns' => Proc::DGcols([
        'columns' => [
            'idNaklad.naklad_id',
            [
                'attribute' => 'idNaklad.naklad_date',
                'format' => 'date',
            ],
            [
                'attribute' => 'idNaklad.idMolRelease.idperson.auth_user_fullname',
                'label' => 'ФИО МОЛ, кто отпустил',
            ],
            [
                'attribute' => 'idNaklad.idMolRelease.iddolzh.dolzh_name',
                'label' => 'Должность МОЛ, кто отпустил',
            ],
            [
                'attribute' => 'idNaklad.idMolRelease.idpodraz.podraz_name',
                'label' => 'Подразделение МОЛ, кто отпустил',
                'visible' => false,
            ],
            [
                'attribute' => 'idNaklad.idMolRelease.idbuild.build_name',
                'label' => 'Здание МОЛ, кто отпустил',
            ],
            [
                'attribute' => 'idNaklad.idMolGot.idperson.auth_user_fullname',
                'label' => 'ФИО МОЛ, кто принял',
            ],
            [
                'attribute' => 'idNaklad.idMolGot.iddolzh.dolzh_name',
                'label' => 'Должность МОЛ, кто принял',
            ],
            [
                'attribute' => 'idNaklad.idMolGot.idpodraz.podraz_name',
                'label' => 'Подразделение МОЛ, кто принял',
                'visible' => false,
            ],
            [
                'attribute' => 'idNaklad.idMolGot.idbuild.build_name',
                'label' => 'Здание МОЛ, кто принял',
            ],
            'nakladmaterials_number',
        ],
        'buttons' => array_merge([
            'nakladreport' => function ($url, $model) use ($params) {
                return Html::button('<i class="glyphicon glyphicon-list"></i>', [
                    'type' => 'button',
                    'title' => 'Скачать требование-накладную',
                    'class' => 'btn btn-xs btn-default',
                    'onclick' => 'DownloadReport("' . Url::to(['Fregat/naklad/naklad-report']) . '", null, {id: ' . $model->id_naklad . '} )'
                ]);
            },
        ], Yii::$app->user->can('NakladEdit') ? [
            'update' => ['Fregat/naklad/update', 'id_naklad'],
        ] : []),
    ]),
    'gridOptions' => [
        'dataProvider' => $dataProvider_naklad,
        'filterModel' => $searchModel_naklad,
        'panel' => [
            'heading' => '<i class="glyphicon glyphicon-bell"></i> Требования-накладные',
        ],
    ]
]));