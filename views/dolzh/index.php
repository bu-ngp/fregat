<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;
use yii\web\Session;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DolzhSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Должности';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="dolzh-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    $session = new Session;
    $session->open();
    
    echo DynaGrid::widget(Proc::DGopts([
                'columns' => Proc::DGcols([
                    'columns' => [
                        'dolzh_name',
                    ],
                    'buttons' => array_merge(
                            isset($session[$foreignmodel]['foreign']) ? [
                                'choose' => function ($url, $model, $key) use ($session, $foreignmodel) {
                                    $field = $session[$foreignmodel]['foreign']['field'];
                                    $customurl = Url::to([$session[$foreignmodel]['foreign']['url'], 'id' => $session[$foreignmodel]['foreign']['id'], $foreignmodel => [$field => $model['dolzh_id']]]);
                                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-ok-sign"></i>', $customurl, ['title' => 'Выбрать', 'class' => 'btn btn-xs btn-success']);
                                }] : [], [
                                'update' => ['dolzh/update', 'dolzh_id'],
                                'delete' => ['dolzh/delete', 'dolzh_id'],
                                    ]
                            ),
                        ]),
                        'gridOptions' => [
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'options' => ['id' => 'dolzhgrid'],
                        ]
            ]));

            $session->close();
            ?>

</div>
