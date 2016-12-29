<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\TrMatOsmotrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Материалы требующие восстановления';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="tr-mat-osmotr-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    echo DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'trmatosmotrgrid'],
                'columns' => Proc::DGcols([
                    'columns' => [
                        [
                            'attribute' => 'idTrMat.idMattraffic.idMaterial.material_name',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return '<a data-pjax="0" href="' . Url::to(['Fregat/material/update', 'id' => $model->idTrMat->idMattraffic->id_material]) . '">' . $model->idTrMat->idMattraffic->idMaterial->material_name . '</a>';
                            }
                        ],
                        'idTrMat.idMattraffic.idMaterial.material_inv',
                        'tr_mat_osmotr_number',
                        [
                            'attribute' => 'idTrMat.idMattraffic.idMol.idperson.auth_user_fullname',
                            'label' => 'ФИО материально-ответственного лица',
                        ],
                        [
                            'attribute' => 'idTrMat.idMattraffic.idMol.iddolzh.dolzh_name',
                            'label' => 'Должность материально-ответственного лица',
                        ],
                        [
                            'attribute' => 'idTrMat.idMattraffic.idMol.idbuild.build_name',
                            'label' => 'Здание материально-ответственного лица',
                        ],
                        [
                            'attribute' => 'idTrMat.idParent.idMaterial.material_name',
                            'label' => 'Укомплектовано в матер-ую цен-ть',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return '<a data-pjax="0" href="' . Url::to(['Fregat/material/update', 'id' => $model->idTrMat->idParent->id_material]) . '">' . $model->idTrMat->idParent->idMaterial->material_name . '</a>';
                            }
                        ],
                        [
                            'attribute' => 'idTrMat.idParent.idMaterial.material_inv',
                            'label' => 'Инвентаный номер мат-ой цен-ти в которую укомплектован материал',
                        ],
                        'idOsmotraktmat.osmotraktmat_id',
                        [
                            'attribute' => 'idOsmotraktmat.osmotraktmat_date',
                            'format' => 'date',
                        ],
                        [
                            'attribute' => 'idOsmotraktmat.idMaster.idperson.auth_user_fullname',
                            'label' => 'ФИО мастера',
                        ],
                        [
                            'attribute' => 'idOsmotraktmat.idMaster.iddolzh.dolzh_name',
                            'label' => 'Должность мастера',
                        ],
                        'idReason.reason_text',
                        'tr_mat_osmotr_comment',
                    ],
                    'buttons' => array_merge(
                            empty($foreign) ? [] : [
                                'chooseajax' => ['Fregat/tr-mat-osmotr/assign-to-grid']]
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

