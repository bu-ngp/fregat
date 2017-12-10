<?php

use app\models\Fregat\Mattraffic;
use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use app\models\Fregat\Material;
use yii\helpers\Url;

\Yii::$app->getView()->registerJsFile('@web/js/materialfilter.js' . Proc::appendTimestampUrlParam(Yii::$app->basePath . '/web/js/materialfilter.js'));

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
    $mattraffic_tip = Mattraffic::VariablesValues('mattraffic_tip');

    echo DynaGrid::widget(Proc::DGopts([
        'options' => ['id' => 'materialgrid'],
        'columns' => Proc::DGcols([
            'buttonsfirst' => true,
            'columns' => [
                [
                    'attribute' => 'material_tip',
                    'filter' => $material_tip,
                    'value' => function ($model) use ($material_tip) {
                        return $material_tip[$model->material_tip] ?: '';
                    },
                ],
                'idMatv.matvid_name',
                [
                    'attribute' => 'material_name',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if (Yii::$app->user->can('MaterialEdit')) {
                            return '<a data-pjax="0" href="' . Url::to(['Fregat/material/update', 'id' => $model->primaryKey]) . '">' . $model->material_name . '</a>';
                        } else {
                            return $model->material_name;
                        }
                    }
                ],
                [
                    'attribute' => 'material_name1c',
                    'visible' => false,
                ],
                [
                    'attribute' => 'material_1c',
                    'visible' => false,
                ],
                'material_inv',
                [
                    'attribute' => 'material_number',
                    'pageSummary' => function ($summary, $data, \kartik\grid\DataColumn $widget) {
                        /** @var \yii\db\ActiveQuery $query */
                        $query = $widget->grid->dataProvider->query;
                        return $query->sum('material_number');
                    },
                ],
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
                    'pageSummary' => function ($summary, $data, \kartik\grid\DataColumn $widget) {
                        /** @var \yii\db\ActiveQuery $query */
                        $query = $widget->grid->dataProvider->query;
                        $sum = $query->andWhere(['material_writeoff' => 1])->sum('material_writeoff');
                        return 'Списано: ' . ($sum ?: 0);
                    },
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
                [
                    'attribute' => 'currentMattraffic.idMol.idperson.auth_user_fullname',
                    'visible' => false,
                    'label' => 'ФИО текущего МОЛ',
                    'value' => function ($model) {
                        return in_array($model->material_tip, [Material::OSNOV, Material::OSNOV_R, Material::GROUP_UCHET, Material::V_KOMPLEKTE]) ? $model->currentMattraffic->idMol->idperson->auth_user_fullname : '';
                    },
                ],
                [
                    'attribute' => 'currentMattraffic.idMol.iddolzh.dolzh_name',
                    'visible' => false,
                    'label' => 'Должность текущего МОЛ',
                    'value' => function ($model) {
                        return in_array($model->material_tip, [Material::OSNOV, Material::OSNOV_R, Material::GROUP_UCHET, Material::V_KOMPLEKTE]) ? $model->currentMattraffic->idMol->iddolzh->dolzh_name : '';
                    },
                ],
                [
                    'attribute' => 'currentMattraffic.idMol.idbuild.build_name',
                    'visible' => false,
                    'label' => 'Здание текущего МОЛ',
                    'value' => function ($model) {
                        return in_array($model->material_tip, [Material::OSNOV, Material::OSNOV_R, Material::GROUP_UCHET, Material::V_KOMPLEKTE]) ? $model->currentMattraffic->idMol->idbuild->build_name : '';
                    },
                ],
                [
                    'attribute' => 'currentMattraffic.mattraffic_date',
                    'format' => 'date',
                    'label' => 'Дата последнего изменения состояния',
                    'visible' => false,
                ],
                [
                    'attribute' => 'idSchetuchet.schetuchet_kod',
                    'visible' => false,
                ],
                [
                    'attribute' => 'idSchetuchet.schetuchet_name',
                    'visible' => false,
                ],
                [
                    'attribute' => 'material_comment',
                    'visible' => false,
                ],
                [
                    'attribute' => 'lastMattraffic.mattraffic_tip',
                    'label' => 'Тип последней операции',
                    'filter' => $mattraffic_tip,
                    'value' => function ($model) use ($mattraffic_tip) {
                        return isset($mattraffic_tip[$model->lastMattraffic->mattraffic_tip]) ? $mattraffic_tip[$model->lastMattraffic->mattraffic_tip] : '';
                    },
                    'visible' => false,
                ],
                [
                    'attribute' => 'lastMattraffic.mattraffic_date',
                    'format' => 'date',
                    'label' => 'Дата последней операции',
                    'visible' => false,
                ],
                [
                    'attribute' => 'lastInstallMattraffic',
                    'value' => function ($model) {
                        if (isset($model->lastInstallMattraffic->idMol->idbuild->build_name) && isset($model->lastInstallMattraffic->trOsnovs[0]->idCabinet->cabinet_name)) {
                            return $model->lastInstallMattraffic->idMol->idbuild->build_name . ', ' . $model->lastInstallMattraffic->trOsnovs[0]->idCabinet->cabinet_name;
                        }

                        return '';
                    },
                    'visible' => false,
                ],
            ],
            'buttons' => array_merge(
                empty($foreign) ? (Yii::$app->user->can('MaterialEdit') ? [
                    'karta' => function ($url, $model) {
                        $customurl = Yii::$app->getUrlManager()->createUrl(['Fregat/material/update', 'id' => $model->material_id]);
                        return \yii\helpers\Html::a('<i class="glyphicon glyphicon-pencil"></i>', $customurl, ['title' => 'Карта материальной ценности', 'class' => 'btn btn-xs btn-warning', 'data-pjax' => '0']);
                    }
                ] : []) : [
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
                        'onclick' => 'ExportExcel("MaterialSearch","' . \yii\helpers\Url::to('Fregat/material/toexcel') . '", $(this)[0].id, undefined, ' . (YII_ENV === 'test' ? 0 : 1) . ');'
                    ]) . '{export}{dynagrid}',
                ],
            ],
            'afterHeader' => $filter,
            'showPageSummary' => true,
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
