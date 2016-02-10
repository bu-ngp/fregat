<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;
use yii\web\Session;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BuildSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Здания';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="build-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="btn-group">
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']); ?>
    </div>

    <?php
    $session = new Session;
    $session->open();
    
    $result = $session['breadcrumbs'];
    end($result);
    $foreign = isset($result[key($result)]['dopparams']['foreign']) ? $result[key($result)]['dopparams']['foreign'] : '';

    echo DynaGrid::widget(Proc::DGopts([
                'columns' => Proc::DGcols([
                    'columns' => [
                        'build_name',
                    ],
                    'buttons' => array_merge(
                            empty($foreign) ? [] : [
                                'choose' => function ($url, $model, $key) use ($foreign) {
                                    $customurl = Url::to([$foreign['url'], 'id' => $foreign['id'], $foreign['model'] => [$foreign['field'] => $model['build_id']]]);
                                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-ok-sign"></i>', $customurl, ['title' => 'Выбрать', 'class' => 'btn btn-xs btn-success']);
                                }] , [
                                    
                                'update' => ['build/update', 'build_id'],
                                'delete' => ['build/delete', 'build_id'],
                                    ]
                            ),
                        ]),
                        'gridOptions' => [
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'options' => ['id' => 'buildgrid'],
                        ]
            ]));

            $session->close();
            ?>

</div>