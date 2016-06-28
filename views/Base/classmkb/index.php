<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Base\ClassmkbSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'МКБ-10';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="classmkb-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';
    $patienttype = filter_input(INPUT_GET, 'patienttype');

    echo DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'classmkbgrid'],
                'columns' => Proc::DGcols([
                    'buttonsfirst' => true,
                    'columns' => [
                        'code',
                        'name',
                    ],
                    'buttons' => empty($foreign) ? [] : [
                        'chooseajax' => ['Base/classmkb/assign-to-glaukuchet']
                            ],
                ]),
                'gridOptions' => [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'panel' => [
                        'heading' => '<i class="glyphicon glyphicon-heart-empty"></i> ' . $this->title,
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