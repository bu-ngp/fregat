<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\RecoverysendaktSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Журнал восстановления материальных ценностей';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="recoverysendakt-index">
    <?=
    DynaGrid::widget(Proc::DGopts([
        'options' => ['id' => 'recoverysendaktgrid'],
        'columns' => Proc::DGcols([
            'buttonsfirst' => true,
            'columns' => [
                'recoverysendakt_id',
                [
                    'attribute' => 'recoverysendakt_date',
                    'format' => 'date',
                ],
                'idOrgan.organ_name',
            ],
            'buttons' => array_merge(Yii::$app->user->can('RecoveryEdit') ? [
                'update' => ['Fregat/recoverysendakt/update', 'recoverysendakt_id'],
                'deleteajax' => ['Fregat/recoverysendakt/delete', 'recoverysendakt_id'],
            ] : []
            ),
        ]),
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'heading' => '<i class="glyphicon glyphicon-wrench"></i> ' . $this->title,
                'before' => Yii::$app->user->can('RecoveryEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
            ],
        ]
    ]));
    ?>
</div>
<div class="form-group">
    <div class="panel panel-default">
        <div class="panel-heading">
            
            <?= Html::button('<i class="glyphicon glyphicon-list"></i> Выгрузка', ['id' => 'DownloadExportReport', 'class' => 'btn btn-success', 'onclick' => 'ExportExcel("RecoverysendaktSearch","' . \yii\helpers\Url::toRoute('Fregat/recoverysendakt/toexcel') . '", $(this)[0].id);']); ?>
        </div>
    </div>
</div>