<?php
\Yii::$app->getView()->registerJsFile(Yii::$app->request->baseUrl .'/js/authitemfilter.js');

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\BuildSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Авторизационные единицы';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="authitem-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';
    $type = \app\models\Config\Authitem::VariablesValues('type');

    echo DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'authitemgrid'],
                'columns' => Proc::DGcols([
                    'columns' => array_merge(
                            ['description'], !isset($foreign['model']) || (isset($foreign['model']) && $foreign['model'] !== 'Authassignment') ? [
                                [
                                    'attribute' => 'type',
                                    'filter' => $type,
                                    'value' => function ($model) use ($type) {
                                        return isset($type[$model->type]) ? $type[$model->type] : '';
                                    },
                                ],
                                'name'
                                    ] : []
                    ),
                    'buttons' => array_merge(
                            empty($foreign) ? [] : [
                                'chooseajax' => ['Config/authitem/assign-to-grid']], Yii::$app->user->can('RoleEdit') ? [
                                'update' => ['Config/authitem/update', 'name'],
                                'deleteajax' => ['Config/authitem/delete', 'name'],
                                    ] : []
                    ),
                ]),
                'gridOptions' => [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'panel' => [
                        'heading' => '<i class="glyphicon glyphicon-align-justify"></i> ' . $this->title,
                        'before' => Yii::$app->user->can('RoleEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
                    ],
                    'toolbar' => [
                        'base' => ['content' => \yii\bootstrap\Html::a('<i class="glyphicon glyphicon-filter"></i>', ['filter'], [
                                'title' => 'Дополнительный фильтр',
                                'class' => 'btn btn-default filter_button'
                            ]) . \yii\bootstrap\Html::button('<i class="glyphicon glyphicon-floppy-disk"></i>', [
                                'id' => 'Authitemexcel',
                                'type' => 'button',
                                'title' => 'Экспорт в Excel',
                                'class' => 'btn btn-default button_export',
                                'onclick' => 'ExportExcel("AuthitemSearch","' . \yii\helpers\Url::toRoute('Config/authitem/toexcel') . '", $(this)[0].id );'
                            ]) . '{export}{dynagrid}',
                        ],
                    ],
                    'afterHeader' => $filter,
                ]
    ]));
    ?>


    <?php
    yii\bootstrap\Modal::begin([
        'header' => 'Дополнительный фильтр',
        'id' => 'AuthitemFilter',
        'options' => [
            'class' => 'modal_filter',
            'tabindex' => false, // чтобы работал select2 в модальном окне
        ],
    ]);
    yii\bootstrap\Modal::end();
    ?>

</div>
