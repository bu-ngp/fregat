<?php
use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;

\Yii::$app->getView()->registerJsFile('@web/js/recoverysendaktfilter.js' . Proc::appendTimestampUrlParam(Yii::$app->basePath . '/web/js/recoverysendaktfilter.js'));

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
            'toolbar' => [
                'base' => ['content' => \yii\bootstrap\Html::a('<i class="glyphicon glyphicon-filter"></i>', ['recoverysendaktfilter'], [
                        'title' => 'Дополнительный фильтр',
                        'class' => 'btn btn-default filter_button'
                    ]) . '{export}{dynagrid}',
                ],
            ],
            'afterHeader' => $filter,
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
<?php
yii\bootstrap\Modal::begin([
    'header' => 'Дополнительный фильтр',
    'id' => 'RecoverysendaktFilter',
    'options' => [
        'class' => 'modal_filter',
        'tabindex' => false, // чтобы работал select2 в модальном окне
    ],
]);
yii\bootstrap\Modal::end();
?>