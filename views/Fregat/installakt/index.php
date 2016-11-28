<?php
\Yii::$app->getView()->registerJsFile(Yii::$app->request->baseUrl . '/js/installaktfilter.js');

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
                        //   'visible' => false,
                    ],
                    [
                        'attribute' => 'idInstaller.idperson.auth_user_fullname',
                        'label' => 'ФИО мастера',
                    ],
                    [
                        'attribute' => 'idInstaller.iddolzh.dolzh_name',
                        'label' => 'Должность мастера',
                    ],
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
                ],
                'toolbar' => [
                    'base' => ['content' => \yii\bootstrap\Html::a('<i class="glyphicon glyphicon-filter"></i>', ['installaktfilter'], [
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
    'id' => 'InstallaktFilter',
    'options' => [
        'class' => 'modal_filter',
        'tabindex' => false, // чтобы работал select2 в модальном окне
    ],
]);
yii\bootstrap\Modal::end();
?>