<?php

use yii\helpers\Html;
use app\func\Proc;
use kartik\dynagrid\DynaGrid;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\ImportemployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
//var_dump($this);
$this->title = 'Импорт сотрудников';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="importemployee-index">
    <?=
    DynaGrid::widget(Proc::DGopts([
                'columns' => Proc::DGcols([
                    'columns' => [
                        'importemployee_combination',
                        'idpodraz.podraz_name',
                        'idbuild.build_name',
                    ],
                    'buttons' => [
                        'update' => ['Fregat/importemployee/update', 'importemployee_id'],
                        'delete' => ['Fregat/importemployee/delete', 'importemployee_id'],
                    ],
                ]),
                'gridOptions' => [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'options' => ['id' => 'importemployeegrid'],
                    'panel' => [
                        'heading' => '<i class="glyphicon glyphicon-user"></i> ' . $this->title,
                        'before' => Yii::$app->user->can('FregatImport') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
                    ],
                ]
    ]));
    ?>

</div>
