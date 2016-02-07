<?php

use yii\helpers\Html;
use yii\web\Session;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PodrazSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Подразделения';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="podraz-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    $session = new Session;
    $session->open();

    echo DynaGrid::widget(Proc::DGopts([
                'columns' => Proc::DGcols([
                    'columns' => [
                        'podraz_name',
                    ],
                    'buttons' => array_merge(
                            isset($session[$foreignmodel]['foreign']) ? [
                                'choose' => function ($url, $model, $key) use ($session, $foreignmodel) {
                                    $field = $session[$foreignmodel]['foreign']['field'];
                                    $customurl = Url::to([$session[$foreignmodel]['foreign']['url'], 'id' => $session[$foreignmodel]['foreign']['id'], $foreignmodel => [$field => $model['podraz_id']]]);
                                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-ok-sign"></i>', $customurl, ['title' => 'Выбрать', 'class' => 'btn btn-xs btn-success']);
                                }] : [], [
                                'update' => ['podraz/update', 'podraz_id'],
                                'delete' => ['podraz/delete', 'podraz_id'],
                                    ]
                            ),
                        ]),
                        'gridOptions' => [
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'options' => ['id' => 'podrazgrid'],
                        ]
            ]));

            $session->close();
            ?>

</div>
