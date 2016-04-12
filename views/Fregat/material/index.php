<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

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

    echo DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'materialgrid'],
                'columns' => Proc::DGcols([
                    'columns' => [
                        [
                            'attribute' => 'material_tip',
                            'filter' => [1 => 'Основное средство', 2 => 'Материал'],
                            'value' => function ($model) {
                        return $model->material_tip === 1 ? 'Основное средство' : 'Материал';
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
                            'visible' => false,
                            'value' => function ($model, $key, $index, $column) {
                                return Yii::$app->formatter->asDate($model->material_release);
                            }
                        ],
                        [
                            'attribute' => 'material_writeoff',
                            'filter' => [0 => 'Нет', 1 => 'Да'],
                            'visible' => false,
                            'value' => function ($model) {
                        return $model->material_writeoff === 0 ? 'Нет' : 'Да';
                    },
                        ],
                        [
                            'attribute' => 'material_username',
                            'visible' => false,
                        ],
                        [
                            'attribute' => 'material_lastchange',
                            'visible' => false,
                            'value' => function ($model, $key, $index, $column) {
                                return Yii::$app->formatter->asDatetime($model->material_lastchange);
                            }
                        ],
                        [
                            'attribute' => 'material_importdo',
                            'filter' => [0 => 'Нет', 1 => 'Да'],
                            'visible' => false,
                            'value' => function ($model) {
                        return $model->material_importdo === 0 ? 'Нет' : 'Да';
                    },
                        ],
                    ],
                        /*   'buttons' => array_merge(
                          empty($foreign) ? [] : [
                          'choose' => function ($url, $model, $key) use ($foreign, $iduser) {
                          $customurl = Url::to([$foreign['url'], 'id' => $foreign['id'], 'iduser' => $iduser, $foreign['model'] => [$foreign['field'] => $model['material_id']]]);
                          return \yii\helpers\Html::a('<i class="glyphicon glyphicon-ok-sign"></i>', $customurl, ['title' => 'Выбрать', 'class' => 'btn btn-xs btn-success', 'data-pjax' => '0']);
                          }], Yii::$app->user->can('BuildEdit') ? [
                          'update' => ['Fregat/material/update', 'material_id'],
                          'delete' => ['Fregat/material/delete', 'material_id'],
                          ] :  []
                          ), */
                ]),
                'gridOptions' => [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'panel' => [
                        'heading' => '<i class="glyphicon glyphicon-picture"></i> ' . $this->title,
                    //  'before' => Yii::$app->user->can('BuildEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
                    ],
                ]
    ]));
    ?>

</div>
