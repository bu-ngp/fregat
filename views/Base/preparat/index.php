<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Base\PreparatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Препараты';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="preparat-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    echo DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'preparatgrid'],
                'columns' => Proc::DGcols([
                    'columns' => [
                        'preparat_name',
                    ],
                    'buttons' => array_merge(
                            empty($foreign) ? [] : [
                                'choose' => function ($url, $model, $key) use ($foreign, $patienttype, $idglaukuchet) {
                                    $customurl = Url::to([$foreign['url'], 'patienttype' => $patienttype, 'id' => $foreign['id'], 'idglaukuchet' => $idglaukuchet, $foreign['model'] => [$foreign['field'] => $model['preparat_id']]]);
                                    $customurl2 = [$foreign['url'], 'patienttype' => $patienttype, 'id' => $foreign['id'], 'idglaukuchet' => $idglaukuchet, $foreign['model'] => [$foreign['field'] => $model['preparat_id']]];
                                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-ok-sign"></i>', $customurl, ['title' => 'Выбрать', 'class' => 'btn btn-xs btn-success'/* , 'data' => [
                                                  'confirm' => "Are you sure you want to delete profile?",
                                                  'method' => 'post',
                                                  ] */, 'data-pjax' => '0']);
                                }], Yii::$app->user->can('PreparatEdit') ? [
                                        'update' => ['Base/preparat/update', 'preparat_id'],
                                        'deleteajax' => ['Base/preparat/delete', 'preparat_id'],
                                            ] : []
                            ),
                        ]),
                        'gridOptions' => [
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'panel' => [
                                'heading' => '<i class="glyphicon glyphicon-tint"></i> ' . $this->title,
                                'before' => Yii::$app->user->can('PreparatEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
                            ],
                        ]
            ]));
            ?>

</div>
