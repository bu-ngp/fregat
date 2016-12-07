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
                'attribute' => 'idSpisosnovakt.idMol.idperson.auth_user_fullname',
                'label' => 'ФИО материально-ответственного лица',
            ],
            [
                'attribute' => 'idSpisosnovakt.idMol.iddolzh.dolzh_name',
                'label' => 'Должность материально-ответственного лица',
            ],
            [
                'attribute' => 'idSpisosnovakt.idMol.idpodraz.podraz_name',
                'label' => 'Подразделение материально-ответственного лица',
            ],
            [
                'attribute' => 'idSpisosnovakt.idMol.idbuild.build_name',
                'label' => 'Здание материально-ответственного лица',
            ],

            [
                'attribute' => 'idSpisosnovakt.idEmployee.idperson.auth_user_fullname',
                'label' => 'ФИО иного лица',
                'visible' => false,
            ],
            [
                'attribute' => 'idSpisosnovakt.idEmployee.iddolzh.dolzh_name',
                'label' => 'Должность иного лица',
                'visible' => false,
            ],
            [
                'attribute' => 'idSpisosnovakt.idEmployee.idpodraz.podraz_name',
                'label' => 'Подразделение иного лица',
                'visible' => false,
            ],
            [
                'attribute' => 'idSpisosnovakt.idEmployee.idbuild.build_name',
                'label' => 'Здание иного лица',
                'visible' => false,
            ],
            'spisosnovmaterials_number',
        ],
        'buttons' => array_merge([
            'spisosnovaktreport' => function ($url, $model) use ($params) {
                return Html::button('<i class="glyphicon glyphicon-list"></i>', [
                    'type' => 'button',
                    'title' => 'Скачать заявку на списание основных средств',
                    'class' => 'btn btn-xs btn-default',
                    'onclick' => 'DownloadReport("' . Url::to(['Fregat/spisosnovakt/spisosnovakt-report']) . '", null, {id: ' . $model->id_spisosnovakt . '} )'
                ]);
            },
        ],
            Yii::$app->user->can('SpisosnovaktEdit') ? [
                'spisosnovaktview' => function ($url, $model) use ($params) {
                    $customurl = Yii::$app->getUrlManager()->createUrl(['Fregat/spisosnovakt/update', 'id' => $model->id_spisosnovakt]);
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $customurl, ['title' => 'Открыть', 'class' => 'btn btn-xs btn-success', 'data-pjax' => '0']);
                },
            ] : []
        ),
    ]),
    'gridOptions' => [
        'dataProvider' => $dataProvider_spisosnovakt,
        'filterModel' => $searchModel_spisosnovakt,
        'panel' => [
            'heading' => '<i class="glyphicon glyphicon-paste"></i> Списание, как основная материальная ценность',
        ],
    ]
]));