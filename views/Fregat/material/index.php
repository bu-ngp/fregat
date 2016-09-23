<?php
\Yii::$app->getView()->registerJsFile(Yii::$app->request->baseUrl . '/js/materialfilter.js');

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\ButtonGroup;
use app\models\Fregat\Material;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\MaterialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Журнал материальных ценностей';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="material-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';
    $material_tip = Material::VariablesValues('material_tip');
    $material_writeoff = Material::VariablesValues('material_writeoff');
    $material_importdo = Material::VariablesValues('material_importdo');

    echo DynaGrid::widget(Proc::DGopts([
        'options' => ['id' => 'materialgrid'],
        'columns' => Proc::DGcols([
            'buttonsfirst' => true,
            'columns' => [                
                [
                    'attribute' => 'material_tip',
                    'filter' => $material_tip,
                    'value' => function ($model) use ($material_tip) {
                        return isset($material_tip[$model->material_tip]) ? $material_tip[$model->material_tip] : '';
                    },
                ],
                'idMatv.matvid_name',
                'material_name',
                'material_inv',
                'material_number',
                'idIzmer.izmer_name',
                'material_price',
                [
                    'attribute' => 'material_serial',
                    'visible' => false,
                ],
                [
                    'attribute' => 'material_release',
                    'format' => 'date',
                    'visible' => false,
                ],
                [
                    'attribute' => 'material_writeoff',
                    'filter' => $material_writeoff,
                    'value' => function ($model) use ($material_writeoff) {
                        return isset($material_writeoff[$model->material_writeoff]) ? $material_writeoff[$model->material_writeoff] : '';
                    },
                ],
                [
                    'attribute' => 'material_username',
                    'visible' => false,
                ],
                [
                    'attribute' => 'material_lastchange',
                    'format' => 'datetime',
                    'visible' => false,
                ],
                [
                    'attribute' => 'material_importdo',
                    'filter' => $material_importdo,
                    'value' => function ($model) use ($material_importdo) {
                        return isset($material_importdo[$model->material_importdo]) ? $material_importdo[$model->material_importdo] : '';
                    },
                    'visible' => false,
                ],
                [
                    'attribute' => 'mattraffics.mattraffic_username',
                    'value' => function ($model) {
                        return $model->mattraffics[0]->mattraffic_username;
                    },
                    'visible' => false,
                ],
                [
                    'attribute' => 'mattraffics.mattraffic_lastchange',
                    'value' => function ($model) {
                        return Yii::$app->formatter->asDatetime($model->mattraffics[0]->mattraffic_lastchange);
                    },
                    'visible' => false,
                ],
            ],
            'buttons' => array_merge(
                empty($foreign) ? [
                    'karta' => function ($url, $model) {
                        $customurl = Yii::$app->getUrlManager()->createUrl(['Fregat/material/update', 'id' => $model->material_id]);
                        return \yii\helpers\Html::a('<i class="glyphicon glyphicon-pencil"></i>', $customurl, ['title' => 'Карта материальной ценности', 'class' => 'btn btn-xs btn-warning', 'data-pjax' => '0']);
                    }
                ] : [
                    'chooseajax' => ['Fregat/material/assign-to-select2']
                ]
            ),
        ]),
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'heading' => '<i class="glyphicon glyphicon-picture"></i> ' . $this->title,
                'before' => Yii::$app->user->can('MaterialEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Составить акт прихода материальнной ценности', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
            ],
            'toolbar' => [
                'base' => ['content' => \yii\bootstrap\Html::a('<i class="glyphicon glyphicon-filter"></i>', ['materialfilter'], [
                        'title' => 'Дополнительный фильтр',
                        'class' => 'btn btn-default filter_button'
                    ]) . \yii\bootstrap\Html::button('<i class="glyphicon glyphicon-floppy-disk"></i>', [
                        'id' => 'Materialexcel',
                        'type' => 'button',
                        'title' => 'Экспорт в Excel',
                        'class' => 'btn btn-default button_export',
                        'onclick' => 'ExportExcel("MaterialSearch","' . \yii\helpers\Url::toRoute('Fregat/material/toexcel') . '", $(this)[0].id);'
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
        'id' => 'MaterialFilter',
        'options' => [
            'class' => 'modal_filter',
            'tabindex' => false, // чтобы работал select2 в модальном окне
        ],
    ]);
    yii\bootstrap\Modal::end();
    ?>

</div>
