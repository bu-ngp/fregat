<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\BuildSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Здания';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="build-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    echo DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'buildgrid'],
                'columns' => Proc::DGcols([
                    'columns' => [
                        'build_name',
                    ],
                    'buttons' => array_merge(
                            empty($foreign) ? [] : [
                                'choose' => function ($url, $model, $key) use ($foreign, $iduser) {
                                    $customurl = Url::to([$foreign['url'], 'id' => $foreign['id'], 'iduser' => $iduser, $foreign['model'] => [$foreign['field'] => $model['build_id']]]);
                                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-ok-sign"></i>', $customurl, ['title' => 'Выбрать', 'class' => 'btn btn-xs btn-success', 'data-pjax' => '0']);
                                }], Yii::$app->user->can('BuildEdit') ? [
                                        'update' => ['Fregat/build/update', 'build_id'],
                                        'delete' => ['Fregat/build/delete', 'build_id'],
                                            ] : []
                            ),
                        ]),
                        'gridOptions' => [
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'panel' => [
                                'heading' => '<i class="glyphicon glyphicon-home"></i> ' . $this->title,
                                'before' => Yii::$app->user->can('BuildEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
                            ],
                        ]
            ]));
            ?>

        </div>
        <div class="form-group">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Назад', Proc::GetPreviousURLBreadcrumbsFromSession(), ['class' => 'btn btn-info']) ?>
        </div>
    </div>
</div>