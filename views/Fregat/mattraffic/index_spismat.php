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
                'chooseajax' => ['Fregat/mattraffic/assign-to-spismatmaterials-grid']
            ],
            'columns' => [
                [
                    'attribute' => 'idMaterial.material_name',
                    'label' => 'Наименование, куда установлено',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if (isset($model->trMats[0]))
                            return '<a data-pjax="0" href="' . Url::to(['Fregat/material/update', 'id' => $model->id_material]) . '">' . $model->idMaterial->material_name . '</a>';
                    },
                ],
                'idMaterial.material_name',
                'idMaterial.material_inv',
                'mattraffic_number',
                [
                    'attribute' => 'trMats.idParent.idMaterial.material_name',
                    'label' => 'Наименование, куда установлено',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if (isset($model->trMats[0]))
                            return '<a data-pjax="0" href="' . Url::to(['Fregat/material/update', 'id' => $model->trMats[0]->idParent->id_material]) . '">' . $model->trMats[0]->idParent->idMaterial->material_name . '</a>';
                    },
                ], [
                    'attribute' => 'trMats.idParent.idMaterial.material_inv',
                    'label' => 'Инвентарный номер, куда установлено',
                    'value' => function ($model) {
                        if (isset($model->trMats[0]))
                            return $model->trMats[0]->idParent->idMaterial->material_inv;
                    },
                ], [
                    'attribute' => 'trMats.id_installakt',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if (isset($model->trMats[0]))
                            return '<a data-pjax="0" href="' . Url::to(['Fregat/installakt/update', 'id' => $model->trMats[0]->id_installakt]) . '">' . $model->trMats[0]->id_installakt . '</a>';
                    },
                ], [
                    'attribute' => 'trMats.idInstallakt.installakt_date',
                    'format' => 'date',
                    'value' => function ($model) {
                        if (isset($model->trMats[0]))
                            return $model->trMats[0]->idInstallakt->installakt_date;
                    },
                ], [
                    'attribute' => 'trMats.idInstallakt.idInstaller.idperson.auth_user_fullname',
                    'label' => 'ФИО мастера',
                    'value' => function ($model) {
                        if (isset($model->trMats[0]))
                            return $model->trMats[0]->idInstallakt->idInstaller->idperson->auth_user_fullname;
                    },
                ], [
                    'attribute' => 'trMats.idInstallakt.idInstaller.iddolzh.dolzh_name',
                    'label' => 'Должность мастера',
                    'value' => function ($model) {
                        if (isset($model->trMats[0]))
                            return $model->trMats[0]->idInstallakt->idInstaller->iddolzh->dolzh_name;
                    },
                ], [
                    'attribute' => 'idMol.idperson.auth_user_fullname',
                    'label' => 'ФИО МОЛ',
                    'visible' => false,
                ], [
                    'attribute' => 'idMol.idpodraz.podraz_name',
                    'label' => 'Подразделение МОЛ',
                    'visible' => false,
                ], [
                    'attribute' => 'idMol.iddolzh.dolzh_name',
                    'label' => 'Должность МОЛ',
                    'visible' => false,
                ], [
                    'attribute' => 'idMol.idbuild.build_name',
                    'label' => 'Здание МОЛ',
                    'visible' => false,
                ],
            ],
        ]),
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'heading' => '<i class="glyphicon glyphicon-th-large"></i> ' . $this->title,
            ],
        ],
    ]));
    ?>

</div>

