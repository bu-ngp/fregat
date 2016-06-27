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

$this->title = 'Журнал перемещений материальных ценностей';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="installakt-index">
    <?=
    DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'installaktgrid'],
                'columns' => Proc::DGcols([
                    'columns' => [
                        'installakt_id',
                        [
                            'attribute' => 'installakt_date',
                            'format' => 'date',
                            'visible' => false,
                        ],
                        'idInstaller.idperson.auth_user_fullname',
                        'idInstaller.iddolzh.dolzh_name',
                    ],
                    'buttons' => array_merge(Yii::$app->user->can('InstallEdit') ? [
                                'update' => ['Fregat/installakt/update', 'installakt_id'],
                                'deleteajax' => ['Fregat/installakt/delete', 'installakt_id'],
                                    ] : []
                    ),
                ]),
                'gridOptions' => [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'panel' => [
                        'heading' => '<i class="glyphicon glyphicon-random"></i> ' . $this->title,
                        'before' => Yii::$app->user->can('InstallEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
                    /* ButtonGroup::widget([
                      'buttons' => [
                      Yii::$app->user->can('EmployeeEdit')  ?
                      ButtonDropdown::widget([
                      'label' => '<i class="glyphicon glyphicon-plus"></i> Составить акт',
                      'encodeLabel' => false,
                      'dropdown' => [
                      'encodeLabels' => false,
                      'items' => [
                      ['label' => 'Составить акт перемещения материальных ценностей в помещении', 'url' => ['Fregat/installakt/createosnov'], 'linkOptions' => ['data-pjax' => '0']],
                      ['label' => 'Составить акт установки материальных ценностей в другую материальную ценность', 'url' => ['Fregat/installakt/createmat'], 'linkOptions' => ['data-pjax' => '0']],
                      ],
                      ],
                      'options' => ['class' => 'btn btn-success']
                      ]) : [],
                      ],
                      ]), */
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