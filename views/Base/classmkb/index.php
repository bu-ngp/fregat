<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Base\ClassmkbSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'МКБ-10';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="classmkb-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';
    $patienttype = filter_input(INPUT_GET, 'patienttype');

    echo DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'classmkbgrid'],
                'columns' => Proc::DGcols([
                    'columns' => [
                        'code',
                        'name',
                    ],
                    'buttons' => empty($foreign) ? [] : [
                        'choose' => function ($url, $model, $key) use ($foreign, $patienttype) {
                            $customurl = Url::to([$foreign['url'], 'id' => $foreign['id'], 'patienttype' => $patienttype, $foreign['model'] => [$foreign['field'] => $model['id']]]);
                            return \yii\helpers\Html::a('<i class="glyphicon glyphicon-ok-sign"></i>', $customurl, ['title' => 'Выбрать', 'class' => 'btn btn-xs btn-success', 'data-pjax' => '0']);
                        }],
                        ]),
                        'gridOptions' => [
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'panel' => [
                                'heading' => '<i class="glyphicon glyphicon-heart-empty"></i> ' . $this->title,
                            ],
                        ]
            ]));
            ?>

</div>