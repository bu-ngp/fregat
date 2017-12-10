<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\TrMatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Установленные комплектующие';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="tr-mat-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    echo DynaGrid::widget(Proc::DGopts([
        'options' => ['id' => 'trmatgrid'],
        'columns' => Proc::DGcols([
            'columns' => [
                [
                    'attribute' => 'idMattraffic.idMaterial.material_name',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return '<a data-pjax="0" href="' . Url::to(['Fregat/material/update', 'id' => $model->idMattraffic->id_material]) . '">' . $model->idMattraffic->idMaterial->material_name . '</a>';
                    }
                ],
                'idMattraffic.idMaterial.material_inv',
                'idMattraffic.mattraffic_number',
                [
                    'attribute' => 'idParent.idMaterial.material_name',
                    'label' => 'Укомплекторано в мат-ую цен-ть',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return '<a data-pjax="0" href="' . Url::to(['Fregat/material/update', 'id' => $model->idParent->id_material]) . '">' . $model->idParent->idMaterial->material_name . '</a>';
                    }
                ],
                [
                    'attribute' => 'idParent.idMaterial.material_inv',
                    'label' => 'Инвентарный номер мат-ой цен-ти, в которую укомплектован материал',
                ],
                [
                    'attribute' => 'idParent.idMol.idbuild.build_name',
                    'label' => 'Здание, где установлено',
                ],
                [
                    'attribute' => 'idParent.trOsnovs.idCabinet.cabinet_name',
                    'label' => 'Кабинет, где установлено',
                    'value' => function ($model) {
                        return $model->idParent->trOsnovs[0]->idCabinet->cabinet_name;
                    },
                ],
                [
                    'attribute' => 'idMattraffic.idMol.idperson.auth_user_fullname',
                    'label' => 'ФИО материально-ответственного лица',
                ],
                [
                    'attribute' => 'idMattraffic.idMol.iddolzh.dolzh_name',
                    'label' => 'Должность материально-ответственного лица',
                ],
            ],
            'buttons' => array_merge(
                empty($foreign) ? [] : [
                    'chooseajax' => ['Fregat/tr-mat/assign-to-grid']]
            ),
        ]),
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'heading' => '<i class="glyphicon glyphicon-align-paste"></i> ' . $this->title,
            ],
        ]
    ]));
    ?>

</div>
