<?php

use yii\helpers\Html;
use app\func\Proc;
use kartik\dynagrid\DynaGrid;

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
                    'columns' => [
                        'idInstallakt.installakt_id',
                        [
                            'attribute' => 'idInstallakt.installakt_date',
                            'format' => 'date',
                        ],
                        'idMattraffic.idMaterial.material_name',
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
                                'chooseajax' => ['Fregat/tr-osnov/assign-to-osmotrakt']
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
<div class="form-group">
    <div class="panel panel-default">
        <div class="panel-heading">
            <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Назад', Proc::GetPreviousURLBreadcrumbsFromSession(), ['class' => 'btn btn-info']) ?>
        </div>
    </div>
</div>

