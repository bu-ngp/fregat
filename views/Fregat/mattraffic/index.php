<?php

use yii\helpers\Html;
use app\func\Proc;
use kartik\dynagrid\DynaGrid;
use yii\helpers\Url;

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

    echo DynaGrid::widget(Proc::DGopts([
                'columns' => Proc::DGcols([
                    'columns' => [
                        [
                            'attribute' => 'mattraffic_date',
                            'value' => function ($model, $key, $index, $column) {
                                return Yii::$app->formatter->asDate($model->mattraffic_date);
                            }
                        ],
                        [
                            'attribute' => 'mattraffic_tip',
                            'filter' => [1 => 'Приход', 2 => 'Списание'],
                            'value' => function ($model) {
                        $mattraffic_tip = '';
                        switch ($model->mattraffic_tip) {
                            case 1:
                                $mattraffic_tip = 'Приход';
                                break;
                            case 2:
                                $mattraffic_tip = 'Списание';
                                break;
                        }

                        return $mattraffic_tip;
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
                            'value' => function ($model, $key, $index, $column) {
                                return Yii::$app->formatter->asDate($model->idMaterial->material_release);
                            }
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
                            'value' => function ($model, $key, $index, $column) {
                                return Yii::$app->formatter->asDate($model->idMol->employee_dateinactive);
                            }
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
                            'value' => function ($model, $key, $index, $column) {
                                return Yii::$app->formatter->asDatetime($model->idMaterial->material_lastchange);
                            }
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
                            'value' => function ($model, $key, $index, $column) {
                                return Yii::$app->formatter->asDatetime($model->idMol->employee_lastchange);
                            }
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
                            'value' => function ($model, $key, $index, $column) {
                                return Yii::$app->formatter->asDatetime($model->mattraffic_lastchange);
                            }
                        ],
                    ],
                ]),
                'gridOptions' => [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'options' => ['id' => 'mattrafficgrid'],
                    'panel' => [
                        'heading' => '<i class="glyphicon glyphicon-th-large"></i> ' . $this->title,
                    ],
                ]
    ]));
    ?>

</div>
