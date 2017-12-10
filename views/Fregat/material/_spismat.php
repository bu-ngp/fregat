<?php
use app\func\Proc;
use app\models\Fregat\Material;
use kartik\dynagrid\DynaGrid;
use yii\bootstrap\Html;
use yii\helpers\Url;

$material_writeoff = Material::VariablesValues('material_writeoff');

echo DynaGrid::widget(Proc::DGopts([
    'options' => ['id' => 'spismatakt_material_grid'],
    'columns' => Proc::DGcols([
        'columns' => [
            'idSpismat.spismat_id',
            [
                'attribute' => 'idSpismat.spismat_date',
                'format' => 'date',
            ],
            [
                'attribute' => 'idSpismat.idMol.idperson.auth_user_fullname',
                'label' => 'ФИО материально-ответственного лица',
            ],
            [
                'attribute' => 'idSpismat.idMol.iddolzh.dolzh_name',
                'label' => 'Должность материально-ответственного лица',
                'visible' => false,
            ],
            [
                'attribute' => 'idSpismat.idMol.idpodraz.podraz_name',
                'label' => 'Подразделение материально-ответственного лица',
                'visible' => false,
            ],
            [
                'attribute' => 'idSpismat.idMol.idbuild.build_name',
                'label' => 'Здание материально-ответственного лица',
                'visible' => false,
            ],
            [
                'attribute' => 'idMattraffic.mattraffic_number',
                'label' => 'Количество установленного материала',
            ],
            [
                'attribute' => 'idMattraffic.trMats.idInstallakt.idInstaller.idperson.auth_user_fullname',
                'label' => 'ФИО установщика',
                'value' => function ($model) {
                    return $model->idMattraffic->trMats[0]->idInstallakt->idInstaller->idperson->auth_user_fullname;
                },
            ],
            [
                'attribute' => 'idMattraffic.trMats.idInstallakt.idInstaller.iddolzh.dolzh_name',
                'label' => 'должность установщика',
                'value' => function ($model) {
                    return $model->idMattraffic->trMats[0]->idInstallakt->idInstaller->iddolzh->dolzh_name;
                },
                'visible' => false,
            ],
            [
                'attribute' => 'idMattraffic.trMats.idInstallakt.idInstaller.idpodraz.podraz_name',
                'label' => 'Подразделение установщика',
                'value' => function ($model) {
                    return $model->idMattraffic->trMats[0]->idInstallakt->idInstaller->idpodraz->podraz_name;
                },
                'visible' => false,
            ],
            [
                'attribute' => 'idMattraffic.trMats.idParent.idMol.idperson.auth_user_fullname',
                'label' => 'ФИО МОЛ, куда установлен материал',
                'value' => function ($model) {
                    return $model->idMattraffic->trMats[0]->idParent->idMol->idperson->auth_user_fullname;
                },
                'visible' => false,
            ],
            [
                'attribute' => 'idMattraffic.trMats.idParent.idMol.idbuild.build_name',
                'label' => 'Здание, где установлен материал',
                'value' => function ($model) {
                    return $model->idMattraffic->trMats[0]->idParent->idMol->idbuild->build_name;
                },
            ],
            [
                'attribute' => 'idMattraffic.trMats.idParent.trOsnovs.idCabinet.cabinet_name',
                'label' => 'Кабинет, где установлен материал',
                'value' => function ($model) {
                    return $model->idMattraffic->trMats[0]->idParent->trOsnovs[0]->idCabinet->cabinet_name;
                },
            ],
            [
                'attribute' => 'idMattraffic.trMats.idParent.idMaterial.material_name',
                'label' => 'Наименовние, куда установлен материал',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<a data-pjax="0" href="' . Url::to(['Fregat/material/update', 'id' => $model->idMattraffic->trMats[0]->idParent->idMaterial->primaryKey]) . '">' . $model->idMattraffic->trMats[0]->idParent->idMaterial->material_name . '</a>';
                }
            ],
            [
                'attribute' => 'idMattraffic.trMats.idParent.idMaterial.material_inv',
                'label' => 'Инвентарный номер, куда установлен материал',
                'value' => function ($model) {
                    return $model->idMattraffic->trMats[0]->idParent->idMaterial->material_inv;
                }
            ],
            [
                'attribute' => 'idMattraffic.trMats.idParent.idMaterial.material_serial',
                'label' => 'Серийный номер, куда установлен материал',
                'value' => function ($model) {
                    return $model->idMattraffic->trMats[0]->idParent->idMaterial->material_serial;
                },
                'visible' => false,
            ],
            [
                'attribute' => 'idMattraffic.trMats.idParent.idMaterial.material_writeoff',
                'label' => 'Списанная материальная ценность, куда установлен материал',
                'filter' => $material_writeoff,
                'value' => function ($model) use ($material_writeoff) {
                    return isset($model->idMattraffic->trMats[0]->idParent->idMaterial->material_writeoff) ? $material_writeoff[$model->idMattraffic->trMats[0]->idParent->idMaterial->material_writeoff] : '';
                },
                'visible' => false,
            ],
        ],
        'buttons' => array_merge([
            'spismatreport' => function ($url, $model) use ($params) {
                return Html::button('<i class="glyphicon glyphicon-list"></i>', [
                    'type' => 'button',
                    'title' => 'Скачать ведомость списания материалов',
                    'class' => 'btn btn-xs btn-default',
                    'onclick' => 'DownloadReport("' . Url::to(['Fregat/spismat/spismat-report']) . '", null, {id: ' . $model->id_spismat . '} )'
                ]);
            },
        ],
            Yii::$app->user->can('SpismatEdit') ? [
                'update' => ['Fregat/spismat/update', 'id_spismat'],
            ] : []
        ),
    ]),
    'gridOptions' => [
        'dataProvider' => $dataProvider_spismat,
        'filterModel' => $searchModel_spismat,
        'panel' => [
            'heading' => '<i class="glyphicon glyphicon-paste"></i> Списание, как матерал',
        ],
    ]
]));