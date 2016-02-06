<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
use app\func\Proc;
use yii\helpers\Url;
use yii\web\View;
use yii\web\Session;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BuildSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Здания';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="build-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <div class="btn-group">
        <?php
        echo Html::a('Добавить', ['create'], ['class' => 'btn btn-success']);
        /*   if (!empty($selectelement)) {
          end($this->params['breadcrumbs']);
          echo Html::a('Выбрать', [$this->params['breadcrumbs'][key($this->params['breadcrumbs']) - 1]['url'], 'class' => 'btn btn-success']);
          } */
        ?>
    </div>
    <?php
    $session = new Session;
    $session->open();

    echo DynaGrid::widget([
        'options' => ['id' => 'dynagrid-1'],
        'storage' => 'cookie',
        'showPersonalize' => true,
        //'allowPageSetting' => false, 
        'allowThemeSetting' => false,
        'allowFilterSetting' => false,
        'allowSortSetting' => false,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn',
                'header' => Html::encode('№')
            ],
            'build_name',
            ['class' => 'kartik\grid\ActionColumn',
                'header' => Html::encode('Действия'),
                'template' => isset($session[$foreignmodel]['foreign']) ? '{choose} {update} {delete}' : '{update} {delete}',
                'buttons' => [
                    'choose' => function ($url, $model, $key) use ($session, $foreignmodel) {
                        $field = $session[$foreignmodel]['foreign']['field'];
                        $customurl = Url::to([$session[$foreignmodel]['foreign']['url'], 'id' => $session[$foreignmodel]['foreign']['id'], $foreignmodel => [$field => $model['build_id']]]);
                        return \yii\helpers\Html::a('<i class="glyphicon glyphicon-ok-sign"></i>', $customurl, ['title' => 'Выбрать', 'class' => 'btn btn-xs btn-success']);
                    },
                            'update' => function ($url, $model) {
                        $customurl = Yii::$app->getUrlManager()->createUrl(['build/update', 'id' => $model['build_id']]);
                        return \yii\helpers\Html::a('<i class="glyphicon glyphicon-pencil"></i>', $customurl, ['title' => 'Обновить', 'class' => 'btn btn-xs btn-warning']);
                    },
                            'delete' => function ($url, $model) {
                        $customurl = Yii::$app->getUrlManager()->createUrl(['build/delete', 'id' => $model['build_id']]);
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
                    // 'export' => false,
                    'exportConfig' => [
                        GridView::EXCEL => [
                            'label' => 'EXCEL',
                            'filename' => 'EXCEL',
                            'options' => ['title' => 'EXCEL List'],
                        ],
                    ],
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'options' => ['id' => 'buildgrid'],
                    'panel' => [ 'type' => GridView::TYPE_DEFAULT,],
                    'toolbar' => [
                        ['content' => '{export} {dynagrid}'],
                    ]
                ]
            ]);

            $session->close();
            ?>

            <?php // var_dump($_SESSION); ?>

</div>