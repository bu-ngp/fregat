<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use yii\web\Session;
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
use app\func\Proc;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PodrazSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Подразделения';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="podraz-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    $session = new Session;
    $session->open();

    echo DynaGrid::widget([
        'options' => ['id' => 'dynagrid-1'],
        'showPersonalize' => true,
        'storage' => 'cookie',
        //'allowPageSetting' => false, 
        'allowThemeSetting' => false,
        'allowFilterSetting' => false,
        'allowSortSetting' => false,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn',
                'header' => Html::encode('№'),
            ],
            'podraz_name',
            ['class' => 'kartik\grid\ActionColumn',
                'header' => Html::encode('Действия'),
                'template' => isset($session[$foreignmodel]['foreign']) ? '{choose} {update} {delete}' : '{update} {delete}',
                'buttons' => [
                    'choose' => function ($url, $model, $key) use ($session, $foreignmodel) {
                        $field = $session[$foreignmodel]['foreign']['field'];
                        $customurl = Url::to([$session[$foreignmodel]['foreign']['url'], 'id' => $session[$foreignmodel]['foreign']['id'], $foreignmodel => [$field => $model['podraz_id']]]);
                        return \yii\helpers\Html::a('<i class="glyphicon glyphicon-ok-sign"></i>', $customurl, ['title' => 'Выбрать', 'class' => 'btn btn-xs btn-success']);
                    },
                            'update' => function ($url, $model) {
                        $customurl = Yii::$app->getUrlManager()->createUrl(['podraz/update', 'id' => $model['podraz_id']]);
                        return \yii\helpers\Html::a('<i class="glyphicon glyphicon-pencil"></i>', $customurl, ['title' => 'Обновить', 'class' => 'btn btn-xs btn-warning']);
                    },
                            'delete' => function ($url, $model) {
                        $customurl = Yii::$app->getUrlManager()->createUrl(['podraz/delete', 'id' => $model['podraz_id']]);
                        return \yii\helpers\Html::a('<i class="glyphicon glyphicon-trash"></i>', $customurl, ['title' => 'Удалить', 'class' => 'btn btn-xs btn-danger', 'data' => [
                                        'confirm' => "Вы уверены, что хотите удалить запись?",
                                        'method' => 'post',
                        ]]);
                    },
                        ],
                        'contentOptions' => ['style' => 'white-space: nowrap;']
                    ],
                ],
                'gridOptions' => [
                    'exportConfig' => [
                        GridView::EXCEL => [
                            'label' => 'EXCEL',
                            'filename' => 'EXCEL',
                            'options' => ['title' => 'EXCEL List'],
                        ],
                    ],
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'options' => ['id' => 'podrazgrid'],
                    'panel' => [ 'type' => GridView::TYPE_DEFAULT,],
                    'toolbar' => [
                        ['content' => '{export} {dynagrid}'],
                    ]
                ]
            ]);

            $session->close();
            ?>

</div>
