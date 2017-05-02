<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;

\Yii::$app->getView()->registerJsFile('@web/js/spismatfilter.js' . Proc::appendTimestampUrlParam(Yii::$app->basePath . '/web/js/spismatfilter.js'));

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\SpismatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Журнал списания материалов';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
    <div class="spismat-index">
        <?=
        DynaGrid::widget(Proc::DGopts([
            'options' => ['id' => 'spismatgrid'],
            'columns' => Proc::DGcols([
                'columns' => [
                    'spismat_id',
                    [
                        'attribute' => 'spismat_date',
                        'format' => 'date',
                    ],
                    [
                        'attribute' => 'idMol.idperson.auth_user_fullname',
                        'label' => 'ФИО МОЛ',
                    ],
                    [
                        'attribute' => 'idMol.iddolzh.dolzh_name',
                        'label' => 'Должность МОЛ',
                    ],
                    [
                        'attribute' => 'idMol.idpodraz.podraz_name',
                        'label' => 'Подразделение МОЛ',
                    ],
                ],
                'buttons' => array_merge(Yii::$app->user->can('SpismatEdit') ? [
                    'downloadreport' => ['Fregat/spismat/spismat-report', 'Скачать ведомость'],
                    'update' => ['Fregat/spismat/update'],
                    'deleteajax' => ['Fregat/spismat/delete'],
                ] : []
                ),
            ]),
            'gridOptions' => [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    'heading' => '<i class="glyphicon glyphicon-th"></i> ' . $this->title,
                    'before' => Yii::$app->user->can('SpismatEdit') ?
                        '<div class="btn-toolbar">' . Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить вручную', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0'])
                        . Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить по актам перемещения', ['create-by-installakt'], ['class' => 'btn btn-info', 'data-pjax' => '0']) . '</div>'
                        : '',
                ],
                'toolbar' => [
                    'base' => ['content' => \yii\bootstrap\Html::a('<i class="glyphicon glyphicon-filter"></i>', ['spismatfilter'], [
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
    'id' => 'SpismatFilter',
    'options' => [
        'class' => 'modal_filter',
        'tabindex' => false, // чтобы работал select2 в модальном окне
    ],
]);
yii\bootstrap\Modal::end();
?>