<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\OsmotraktSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Журнал осмотров материальных ценностей';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="osmotrakt-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    echo DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'osmotraktgrid'],
                'columns' => Proc::DGcols([
                    'buttonsfirst' => true,
                    'columns' => [
                        'osmotrakt_id',
                        [
                            'attribute' => 'idTrosnov.idMattraffic.idMaterial.idMatv.matvid_name',
                            'visible' => false,
                        ],
                        [
                            'attribute' => 'osmotrakt_date',
                            'format' => 'date',
                        ],
                        'idTrosnov.idMattraffic.idMaterial.material_name',
                        'idTrosnov.idMattraffic.idMaterial.material_inv',
                        [
                            'attribute' => 'idTrosnov.idMattraffic.idMaterial.material_serial',
                            'visible' => false,
                        ],
                        [
                            'attribute' => 'idUser.idperson.auth_user_fullname',
                            'visible' => false,
                            'label' => 'ФИО пользоателя',
                        ],
                        [
                            'attribute' => 'idUser.iddolzh.dolzh_name',
                            'visible' => false,
                            'label' => 'Должность пользоателя',
                        ],
                        [
                            'attribute' => 'idTrosnov.idMattraffic.idMol.idperson.auth_user_fullname',
                            'visible' => false,
                            'label' => 'ФИО материально-ответственного лица',
                        ],
                        [
                            'attribute' => 'idTrosnov.idMattraffic.idMol.iddolzh.dolzh_name',
                            'visible' => false,
                            'label' => 'Должность материально-ответственного лица',
                        ],
                        'idTrosnov.tr_osnov_kab',
                        'idTrosnov.idMattraffic.idMol.idbuild.build_name',
                        'idReason.reason_text',
                        'osmotrakt_comment',
                        [
                            'attribute' => 'idMaster.idperson.auth_user_fullname',
                            'label' => 'ФИО составителя акта',
                        ],
                        [
                            'attribute' => 'idMaster.iddolzh.dolzh_name',
                            'visible' => false,
                            'label' => 'Должность составителя акта',
                        ],
                    ],
                    'buttons' => array_merge(
                            empty($foreign) ? [] : [
                                'chooseajax' => ['Fregat/osmotrakt/assign-to-recoveryrecieveakt']], Yii::$app->user->can('OsmotraktEdit') ? [
                                'update' => ['Fregat/osmotrakt/update', 'osmotrakt_id'],
                                'deleteajax' => ['Fregat/osmotrakt/delete', 'osmotrakt_id'],
                                    ] : []
                    ),
                ]),
                'gridOptions' => [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'panel' => [
                        'heading' => '<i class="glyphicon glyphicon-search"></i> ' . $this->title,
                        'before' => Yii::$app->user->can('OsmotraktEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
                    ],
                ]
    ]));
    ?>
</div>
<div class="form-group">
    <div class="panel panel-default">
        <div class="panel-heading">
            <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Назад', Proc::GetPreviousURLBreadcrumbsFromSession(), ['class' => 'btn btn-info']) ?>
        </div>
    </div>
</div>