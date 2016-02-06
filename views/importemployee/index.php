<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use app\func\Proc;
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ImportemployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
//var_dump($this);
$this->title = 'Импорт сотрудников';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="importemployee-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    DynaGrid::widget([
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
            'importemployee_combination',
            'idpodraz.podraz_name',
            'idbuild.build_name',
            ['class' => 'kartik\grid\ActionColumn',
                'header' => Html::encode('Действия'),
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        $customurl = Yii::$app->getUrlManager()->createUrl(['importemployee/update', 'id' => $model['importemployee_id']]);
                        return \yii\helpers\Html::a('<i class="glyphicon glyphicon-pencil"></i>', $customurl, ['title' => 'Обновить', 'class' => 'btn btn-xs btn-warning']);
                    },
                            'delete' => function ($url, $model) {
                        $customurl = Yii::$app->getUrlManager()->createUrl(['importemployee/delete', 'id' => $model['importemployee_id']]);
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
                    'options' => ['id' => 'importemployeegrid'],
                    'panel' => [ 'type' => GridView::TYPE_DEFAULT,],
                    'toolbar' => [
                        ['content' => '{export} {dynagrid}'],
                    ]
                ]
            ]);
            ?>

</div>
