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
                [
                    'attribute' => 'idMaterial.material_name',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return '<a data-pjax="0" href="' . Url::to(['Fregat/material/update', 'id' => $model->id_material]) . '">' . $model->idMaterial->material_name . '</a>';
                    }
                ],
                'idMaterial.material_inv',
                'idMol.idperson.auth_user_fullname',
                'idMol.iddolzh.dolzh_name',
                'idMol.idpodraz.podraz_name',
                'idMol.idbuild.build_name',
                [
                    'attribute' => 'trOsnovs.tr_osnov_kab',
                    'value' => function ($model) {
                        return $model->trOsnovs[0]->tr_osnov_kab;
                    },
                ],
                [
                    'attribute' => 'idMaterial.material_writeoff',
                    'filter' => $material_writeoff,
                    'value' => function ($model) use ($material_writeoff) {
                        return isset($material_writeoff[$model->idMaterial->material_writeoff]) ? $material_writeoff[$model->idMaterial->material_writeoff] : '';
                    },
                    'visible' => false,
                ],
            ],
        ]),
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'rowOptions' => function ($model, $index, $widget, $grid) {
                $class = [];
              /*  if ($model->idMaterial->material_tip == 1) {
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
            ],
        ],
    ]));
    ?>

</div>

