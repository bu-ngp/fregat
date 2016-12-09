<?php
use app\func\Proc;
use app\models\Fregat\Mattraffic;
use kartik\dynagrid\DynaGrid;
use yii\bootstrap\Html;
use yii\helpers\Url;

$mattraffic_tip = Mattraffic::VariablesValues('mattraffic_tip');
echo DynaGrid::widget(Proc::DGopts([
    'options' => ['id' => 'mattraffic_karta_grid'],
    'columns' => Proc::DGcols([
        'columns' => [
            [
                'attribute' => 'mattraffic_id',
                'visible' => false,
            ],
            [
                'attribute' => 'mattraffic_date',
                'format' => 'date',
            ],
            [
                'attribute' => 'mattraffic_tip',
                'filter' => $mattraffic_tip,
                'value' => function ($model) use ($mattraffic_tip) {
                    return isset($mattraffic_tip[$model->mattraffic_tip]) ? $mattraffic_tip[$model->mattraffic_tip] : '';
                },
            ],
            'mattraffic_number',
            [
                'attribute' => 'idMol.idperson.auth_user_fullname',
                'label' => 'Материально-ответственное лицо',
            ],
            [
                'attribute' => 'idMol.iddolzh.dolzh_name',
                'label' => 'Должность материально-ответственного лица',
            ],
            [
                'attribute' => 'idMol.idbuild.build_name',
                'label' => 'Здание материально-ответственного лица',
            ],
            [
                'attribute' => 'mattraffic_username',
                'visible' => false,
            ],
            [
                'attribute' => 'mattraffic_lastchange',
                'format' => 'datetime',
                'visible' => false,
            ],
            [
                'attribute' => 'trOsnovs.tr_osnov_kab',
                'value' => function ($model) {
                    return $model->trOsnovs[0]->tr_osnov_kab;
                },
            ],
            [
                'attribute' => 'trMats.idParent.idMaterial.material_inv',
                'label' => 'Инвент-ый номер мат-ой цен-ти, в которую включен в состав',
                'value' => function ($model) {
                    return $model->trMats[0]->idParent->idMaterial->material_inv;
                },
            ],
        ],
        'buttons' => array_merge([
            'installaktreport' => function ($url, $model) {
                if ($model->mattraffic_tip == 3)
                    $idinstallakt = $model->trOsnovs[0]->id_installakt;
                elseif ($model->mattraffic_tip == 4)
                    $idinstallakt = $model->trMats[0]->id_installakt;

                if ($model->mattraffic_tip == 3 || $model->mattraffic_tip == 4)
                    return Html::button('<i class="glyphicon glyphicon-list"></i>', [
                        'type' => 'button',
                        'title' => 'Скачать акт перемещения матер-ой цен-ти',
                        'class' => 'btn btn-xs btn-default',
                        'onclick' => 'DownloadReport("' . Url::to(['Fregat/installakt/installakt-report']) . '", null, {id: ' . $idinstallakt . '} )'
                    ]);
                else
                    return '';
            }
        ], Yii::$app->user->can('InstallEdit') ? [
            'installaktupdate' => function ($url, $model) use ($params) {
                if (in_array($model->mattraffic_tip, [3, 4])) {
                    $customurl = Yii::$app->getUrlManager()->createUrl(['Fregat/installakt/update', 'id' => $model->trOsnovs[0]->id_installakt]);
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-pencil"></i>', $customurl, ['title' => 'Обновить', 'class' => 'btn btn-xs btn-warning', 'data-pjax' => '0']);
                }
            },
        ] : [],
            Yii::$app->user->can('MaterialMolDelete') ? [
                'deletemol' => function ($url, $model) use ($params) {
                    $customurl = Yii::$app->getUrlManager()->createUrl(['Fregat/mattraffic/delete', 'id' => $model->primaryKey]);
                    return (in_array($model->mattraffic_tip, [1, 2])) ? Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                        'type' => 'button',
                        'title' => 'Удалить',
                        'class' => 'btn btn-xs btn-danger',
                        'onclick' => 'ConfirmDeleteDialogToAjax("Вы уверены, что хотите удалить запись?", "' . $customurl . '", "mattraffic_karta_grid")'
                    ]) : '';
                },
            ] : []),
    ]),
    'gridOptions' => [
        'dataProvider' => $dataProvider_mattraffic,
        'filterModel' => $searchModel_mattraffic,
        'panel' => [
            'heading' => '<i class="glyphicon glyphicon-random"></i> Движение материальной ценности',
            'before' => '<div class="btn-toolbar">' . (Yii::$app->user->can('MolEdit') ? Html::a('<i class="glyphicon glyphicon-education"></i> Сменить Материально-ответственное лицо', ['Fregat/mattraffic/create',
                    'id' => $model->primaryKey,
                ], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '')
                . (Yii::$app->user->can('InstallEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить акт перемещения материальной ценности', ['Fregat/installakt/create',
                    'id' => $model->primaryKey,
                ], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '') . '</div>',
        ],
    ]
]));