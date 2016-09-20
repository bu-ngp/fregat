<?php

use yii\helpers\Html;
use app\func\Proc;
use kartik\dynagrid\DynaGrid;
use yii\helpers\Url;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\ButtonGroup;
use app\models\Fregat\Mattraffic;
use app\models\Fregat\Material;
use app\models\Fregat\Employee;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\MattrafficSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Движение материальных ценностей';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="mattraffic-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    $mattraffic_tip = Mattraffic::VariablesValues('mattraffic_tip');
    $material_tip = Material::VariablesValues('material_tip');
    $material_writeoff = Material::VariablesValues('material_writeoff');
    $material_importdo = Material::VariablesValues('material_importdo');
    $employee_importdo = Employee::VariablesValues('employee_importdo');

    echo DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'mattrafficgrid'],
                'columns' => Proc::DGcols([
                    'buttonsfirst' => true,
                    'buttons' => empty($foreign) ? [] : [
                        'chooseajax' => ['Fregat/mattraffic/assign-to-select2']
                            ],
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
                            'filter' => $material_tip,
                            'value' => function ($model) use ($material_tip) {
                                return isset($material_tip[$model->idMaterial->material_tip]) ? $material_tip[$model->idMaterial->material_tip] : '';
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
                            'format' => 'date',
                            'visible' => false,
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
                            'format' => 'date',
                            'visible' => false,
                        ],
                        [
                            'attribute' => 'idMaterial.material_writeoff',
                            'filter' => $material_writeoff,
                            'value' => function ($model) use ($material_writeoff) {
                                return isset($material_writeoff[$model->idMaterial->material_writeoff]) ? $material_writeoff[$model->idMaterial->material_writeoff] : '';
                            },
                            'visible' => false,
                        ],
                        [
                            'attribute' => 'idMaterial.material_username',
                            'visible' => false,
                        ],
                        [
                            'attribute' => 'idMaterial.material_lastchange',
                            'format' => 'datetime',
                            'visible' => false,
                        ],
                        [
                            'attribute' => 'idMaterial.material_importdo',
                            'filter' => $material_importdo,
                            'value' => function ($model) use ($material_importdo) {
                                return isset($material_importdo[$model->idMaterial->material_importdo]) ? $material_importdo[$model->idMaterial->material_importdo] : '';
                            },
                            'visible' => false,
                        ],
                        [
                            'attribute' => 'idMol.employee_username',
                            'visible' => false,
                        ],
                        [
                            'attribute' => 'idMol.employee_lastchange',
                            'format' => 'datetime',
                            'visible' => false,
                        ],
                        [
                            'attribute' => 'idMol.employee_importdo',
                            'filter' => $employee_importdo,
                            'value' => function ($model) use ($employee_importdo) {
                                return isset($employee_importdo[$model->idMol->employee_importdo]) ? $employee_importdo[$model->idMol->employee_importdo] : '';
                            },
                            'visible' => false,
                        ],
                        [
                            'attribute' => 'mattraffic_username',
                            'visible' => false,
                        ],
                        [
                            'attribute' => 'mattraffic_lastchange',
                            'format' => 'datetime',
                            'visible' => false,
                        ],
                    ],
                ]),
                'gridOptions' => [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'rowOptions' => function ($model, $index, $widget, $grid) {
                        $class = [];
                     /*   if ($model->idMaterial->material_tip == 1) {
                            $class = ['class' => 'warning'];
                        } else
                            $class = ['class' => 'danger'];

                        if ($model->idMaterial->material_writeoff == 1) {
                            $class = ['class' => 'spisanie'];
                        }

                        if ($model->mattraffic_tip == 3) {
                            $class = ['class' => 'success'];
                        }*/

                        return $class;
                    },
                            'panel' => [
                                'heading' => '<i class="glyphicon glyphicon-th-large"></i> ' . $this->title,
                            /*  'before' =>
                              ButtonGroup::widget([
                              'buttons' => [
                              Yii::$app->user->can('EmployeeEdit') ?
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
                              Yii::$app->user->can('EmployeeEdit') ?
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
                              ['label' => 'Журнал восстановления материальных ценностей', 'url' => ['Fregat/recoverysendakt/index'], 'linkOptions' => ['data-pjax' => '0']],
                              ['label' => 'Составить акт осмотра материальной ценности <i class="glyphicon glyphicon-search"></i>', 'url' => '#', 'linkOptions' => ['data-pjax' => '0']],
                              ['label' => 'Составить акт восстановления материальных ценностей <i class="glyphicon glyphicon-wrench"></i>', 'url' => '#', 'linkOptions' => ['data-pjax' => '0']],
                              ],
                              ],
                              'options' => ['class' => 'btn btn-warning']
                              ]) : [],
                              Yii::$app->user->can('EmployeeEdit') ?
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
                              ]), */
                            ],
                        ],
            ]));
            ?>

        </div>
        
