<?php

use yii\helpers\Html;
use app\func\Proc;
use kartik\dynagrid\DynaGrid;
use yii\helpers\Url;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\ButtonGroup;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\MattrafficSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Движение материальных ценностей';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
            'addfirst' => [
                'label' => 'Фрегат',
                'url' => Url::toRoute('Fregat/mattraffic/index'),
            ],
            'clearbefore' => empty($foreigndo) ? true : false,
        ]);
?>
<div class="mattraffic-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    $mattraffic_tip = [1 => 'Приход', 2 => 'Списание'];

    echo DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'mattrafficgrid'],
                'columns' => Proc::DGcols([
                    'buttons' => empty($foreign) ? [] : [
                        'choose' => function ($url, $model, $key) use ($foreign, $iduser) {
                            $customurl = Url::to([$foreign['url'], 'id' => $foreign['id'], $foreign['model'] => [$foreign['field'] => $model['mattraffic_id']]]);
                            return \yii\helpers\Html::a('<i class="glyphicon glyphicon-ok-sign"></i>', $customurl, ['title' => 'Выбрать', 'class' => 'btn btn-xs btn-success', 'data-pjax' => '0']);
                        }],
                            'buttonsfirst' => true,
                            'columns' => [
                                [
                                    'attribute' => 'mattraffic_date',
                                    'format' => 'date',
                                ],
                                [
                                    'attribute' => 'mattraffic_tip',
                                    'filter' => $mattraffic_tip,
                                    'value' => function ($model) use ($mattraffic_tip) {
                                        return isset($mattraffic_tip[$model->mattraffic_tip]) ? $mattraffic_tip[$model->mattraffic_tip] : '';
                                    },
                                ],
                                'mattraffic_number',
                                [
                                    'attribute' => 'idMaterial.material_tip',
                                    'filter' => [1 => 'Основное средство', 2 => 'Материал'],
                                    'value' => function ($model) {
                                return $model->idMaterial->material_tip === 1 ? 'Основное средство' : 'Материал';
                            },
                                ],
                                [
                                    'attribute' => 'idMaterial.idMatv.matvid_name',
                                    'visible' => false,
                                ],
                                'idMaterial.material_name',
                                'idMaterial.material_inv',
                                [
                                    'attribute' => 'idMaterial.material_serial',
                                    'visible' => false,
                                ],
                                [
                                    'attribute' => 'idMaterial.material_release',
                                    'visible' => false,
                                    'format' => 'date',
                                ],
                                'idMaterial.material_number',
                                'idMaterial.idIzmer.izmer_name',
                                'idMaterial.material_price',
                                [
                                    'attribute' => 'idMol.employee_id',
                                    'visible' => false,
                                ],
                                'idMol.idperson.auth_user_fullname',
                                'idMol.iddolzh.dolzh_name',
                                'idMol.idpodraz.podraz_name',
                                'idMol.idbuild.build_name',
                                [
                                    'attribute' => 'idMol.employee_dateinactive',
                                    'visible' => false,
                                    'format' => 'date',
                                ],
                                [
                                    'attribute' => 'idMaterial.material_writeoff',
                                    'filter' => [0 => 'Нет', 1 => 'Да'],
                                    'visible' => false,
                                    'value' => function ($model) {
                                return $model->idMaterial->material_writeoff === 0 ? 'Нет' : 'Да';
                            },
                                ],
                                [
                                    'attribute' => 'idMaterial.material_username',
                                    'visible' => false,
                                ],
                                [
                                    'attribute' => 'idMaterial.material_lastchange',
                                    'visible' => false,
                                    'format' => 'datetime',
                                ],
                                [
                                    'attribute' => 'idMaterial.material_importdo',
                                    'filter' => [0 => 'Нет', 1 => 'Да'],
                                    'visible' => false,
                                    'value' => function ($model) {
                                return $model->idMaterial->material_importdo === 0 ? 'Нет' : 'Да';
                            },
                                ],
                                [
                                    'attribute' => 'idMol.employee_username',
                                    'visible' => false,
                                ],
                                [
                                    'attribute' => 'idMol.employee_lastchange',
                                    'visible' => false,
                                    'format' => 'datetime',
                                ],
                                [
                                    'attribute' => 'idMol.employee_importdo',
                                    'filter' => [0 => 'Нет', 1 => 'Да'],
                                    'visible' => false,
                                    'value' => function ($model) {
                                return $model->idMol->employee_importdo === 0 ? 'Нет' : 'Да';
                            },
                                ],
                                [
                                    'attribute' => 'mattraffic_username',
                                    'visible' => false,
                                ],
                                [
                                    'attribute' => 'mattraffic_lastchange',
                                    'visible' => false,
                                    'format' => 'datetime',
                                ],
                            ],
                        ]),
                        'gridOptions' => [
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'rowOptions' => function ($model, $index, $widget, $grid) {
                                $class = [];
                                if ($model->idMaterial->material_tip == 1) {
                                    $class = ['class' => 'warning'];
                                } else {
                                    $class = ['class' => 'danger'];
                                }
                                if ($model->idMaterial->material_writeoff == 1) {
                                    $class = ['class' => 'spisanie'];
                                }
                                return $class;
                            },
                                    'panel' => [
                                        'heading' => '<i class="glyphicon glyphicon-th-large"></i> ' . $this->title,
                                        'before' =>
                                        ButtonGroup::widget([
                                            'buttons' => [
                                                true/* Yii::$app->user->can('EmployeeEdit') */ ?
                                                        ButtonDropdown::widget([
                                                            'label' => '<i class="glyphicon glyphicon-plus"></i> Приход',
                                                            'encodeLabel' => false,
                                                            'dropdown' => [
                                                                'encodeLabels' => false,
                                                                'items' => [
                                                                    ['label' => 'Составить акт прихода материальнной ценности <i class="glyphicon glyphicon-plus-sign"></i>', 'url' => ['Fregat/material/create'], 'linkOptions' => ['data-pjax' => '0']],
                                                                    ['label' => 'Журнал материальных ценностей', 'url' => ['Fregat/material/index'], 'linkOptions' => ['data-pjax' => '0']],
                                                                ],
                                                            ],
                                                            'options' => ['class' => 'btn btn-success']
                                                        ]) : [],
                                                true/* Yii::$app->user->can('EmployeeEdit') */ ?
                                                        ButtonDropdown::widget([
                                                            'label' => '<i class="glyphicon glyphicon-random"></i> Движение',
                                                            'encodeLabel' => false,
                                                            'dropdown' => [
                                                                'encodeLabels' => false,
                                                                'items' => [
                                                                    ['label' => 'Изменить материально ответственное лицо <i class="glyphicon glyphicon-user"></i>', 'url' => '#', 'linkOptions' => ['data-pjax' => '0']],
                                                                    '<li role="presentation" class="divider"></li>',
                                                                    ['label' => 'Журнал перемещений материальных ценностей', 'url' => ['Fregat/installakt/index'], 'linkOptions' => ['data-pjax' => '0']],
                                                                    ['label' => 'Составить акт перемещения материальной ценности <i class="glyphicon glyphicon-random"></i>', 'url' => '#', 'linkOptions' => ['data-pjax' => '0']],
                                                                    '<li role="presentation" class="divider"></li>',
                                                                    ['label' => 'Журнал осмотров материальных ценностей', 'url' => ['Fregat/osmotrakt/index'], 'linkOptions' => ['data-pjax' => '0']],
                                                                    ['label' => 'Журнал восстановления материальных ценностей', 'url' => '#', 'linkOptions' => ['data-pjax' => '0']],
                                                                    ['label' => 'Составить акт осмотра материальной ценности <i class="glyphicon glyphicon-search"></i>', 'url' => '#', 'linkOptions' => ['data-pjax' => '0']],
                                                                    ['label' => 'Составить акт восстановления материальных ценностей <i class="glyphicon glyphicon-wrench"></i>', 'url' => '#', 'linkOptions' => ['data-pjax' => '0']],
                                                                ],
                                                            ],
                                                            'options' => ['class' => 'btn btn-warning']
                                                        ]) : [],
                                                true/* Yii::$app->user->can('EmployeeEdit') */ ?
                                                        ButtonDropdown::widget([
                                                            'label' => '<i class="glyphicon glyphicon-trash"></i> Списание',
                                                            'encodeLabel' => false,
                                                            'dropdown' => [
                                                                'encodeLabels' => false,
                                                                'items' => [
                                                                    ['label' => 'Составить акт списания материальнной ценности <i class="glyphicon glyphicon-trash"></i>', 'url' => '#', 'linkOptions' => ['data-pjax' => '0']],
                                                                    ['label' => 'Журнал списаний материальных ценностей', 'url' => '#', 'linkOptions' => ['data-pjax' => '0']],
                                                                ],
                                                            ],
                                                            'options' => ['class' => 'btn btn-danger']
                                                        ]) : [],
                                            ]
                                        ]),
                                    ],
                                ],
                    ]));
                    ?>

</div>
