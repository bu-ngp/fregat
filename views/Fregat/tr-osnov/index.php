<?php

use yii\helpers\Html;
use app\func\Proc;
use kartik\dynagrid\DynaGrid;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\TrOsnovSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Установленные материальные ценности';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="tr-osnov-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    echo DynaGrid::widget(Proc::DGopts([
        'options' => ['id' => 'tr-osnovgrid'],
        'columns' => Proc::DGcols([
            'buttonsfirst' => true,
            'columns' => [
                'idInstallakt.installakt_id',
                [
                    'attribute' => 'idInstallakt.installakt_date',
                    'format' => 'date',
                ],
                [
                    'attribute' => 'idMattraffic.idMaterial.material_name',
                    'label' => 'Укомплектовано в матер-ую цен-ть',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return '<a data-pjax="0" href="' . Url::to(['Fregat/material/update', 'id' => $model->idMattraffic->id_material]) . '">' . $model->idMattraffic->idMaterial->material_name . '</a>';
                    }
                ],
                'idMattraffic.idMaterial.material_inv',
                'idMattraffic.idMaterial.material_serial',
                'idMattraffic.idMol.idbuild.build_name',
                'tr_osnov_kab',
                [
                    'attribute' => 'idMattraffic.idMol.idperson.auth_user_fullname',
                    'label' => 'ФИО материально-ответственного лица',
                ],
                'idMattraffic.idMol.iddolzh.dolzh_name',
            ],
            'buttons' => array_merge(
                empty($foreign) ? [] : [
                    'chooseajax' => ['Fregat/tr-osnov/assign-to-select2']
                ]
            ),
        ]),
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'heading' => '<i class="glyphicon glyphicon-pushpin"></i> ' . $this->title,
            ],
        ]
    ]));
    ?>

</div>


