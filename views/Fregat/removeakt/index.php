<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\ButtonGroup;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\InstallaktSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Журнал снятия комплектующих материальных ценностей';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="removeakt-index">
    <?=
    DynaGrid::widget(Proc::DGopts([
        'options' => ['id' => 'removeaktgrid'],
        'columns' => Proc::DGcols([
            'columns' => [
                'removeakt_id',
                [
                    'attribute' => 'removeakt_date',
                    'format' => 'date',
                ],
                [
                    'attribute' => 'idRemover.idperson.auth_user_fullname',
                    'label' => 'ФИО демонтажника',
                ],
                [
                    'attribute' => 'idRemover.iddolzh.dolzh_name',
                    'label' => 'Должность демонтажника',
                ],
            ],
            'buttons' => array_merge(Yii::$app->user->can('RemoveaktEdit') ? [
                'update' => ['Fregat/removeakt/update', 'removeakt_id'],
                'deleteajax' => ['Fregat/removeakt/delete', 'removeakt_id'],
            ] : []
            ),
        ]),
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'heading' => '<i class="glyphicon glyphicon-paste"></i> ' . $this->title,
                'before' => Yii::$app->user->can('RemoveaktEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
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