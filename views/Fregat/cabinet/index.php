<?php

use app\func\Proc;
use kartik\dynagrid\DynaGrid;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\CabinetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Кабинеты';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="cabinet-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    echo DynaGrid::widget(Proc::DGopts([
        'options' => ['id' => 'cabinetbybuildgrid'],
        'columns' => Proc::DGcols([
            'columns' => [
                'idbuild.build_name',
                'cabinet_name',
            ],
            'buttons' =>
                empty($foreign) ? [] : [
                    'chooseajax' => ['Fregat/cabinet/assign-to-select2']
                ],
        ]),
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'heading' => '<i class="glyphicon glyphicon-modal-window"></i> ' . $this->title,
            ],
        ]
    ]));
    ?>
</div>
