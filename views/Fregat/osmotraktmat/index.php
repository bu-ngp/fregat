<?php
\Yii::$app->getView()->registerJsFile(Yii::$app->request->baseUrl . '/js/osmotraktmatfilter.js');

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\OsmotraktmatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Журнал осмотров материалов';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
    <div class="osmotraktmat-index">
        <?php
        $result = Proc::GetLastBreadcrumbsFromSession();
        $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

        echo DynaGrid::widget(Proc::DGopts([
            'options' => ['id' => 'osmotraktmatgrid'],
            'columns' => Proc::DGcols([
                'columns' => [
                    'osmotraktmat_id',
                    [
                        'attribute' => 'osmotraktmat_date',
                        'format' => 'date',
                    ],
                    [
                        'attribute' => 'idMaster.idperson.auth_user_fullname',
                        'label' => 'ФИО составителя акта',
                    ],
                    [
                        'attribute' => 'idMaster.iddolzh.dolzh_name',
                        'visible' => false,
                        'label' => 'Должность составителя акта',
                    ],
                    [
                        'attribute' => 'osmotraktmat_countmat',
                    ],
                ],
                'buttons' => array_merge(
                    empty($foreign) ? [
                        'downloadreport' => ['Fregat/osmotraktmat/osmotraktmat-report']] : [
                        'chooseajax' => ['Fregat/osmotrakt/assign-to-grid']], Yii::$app->user->can('OsmotraktEdit') ? [
                    'update' => ['Fregat/osmotraktmat/update'],
                    'deleteajax' => ['Fregat/osmotraktmat/delete'],
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
                'toolbar' => [
                    'base' => ['content' => \yii\bootstrap\Html::a('<i class="glyphicon glyphicon-filter"></i>', ['osmotraktmatfilter'], [
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
<?php
yii\bootstrap\Modal::begin([
    'header' => 'Дополнительный фильтр',
    'id' => 'OsmotraktmatFilter',
    'options' => [
        'class' => 'modal_filter',
        'tabindex' => false, // чтобы работал select2 в модальном окне
    ],
]);
yii\bootstrap\Modal::end();
?>