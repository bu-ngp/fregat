<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BuildSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Здания';
//$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="build-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <div class="btn-group">
        <?php
        echo Html::a('Добавить', ['create'], ['class' => 'btn btn-info']);
        if (!empty($selectelement)) {
            end($this->params['breadcrumbs']);
            echo Html::a('Выбрать', [$this->params['breadcrumbs'][key($this->params['breadcrumbs']) - 1]['url']], ['class' => 'btn btn-success']);
        }
        ?>
    </div>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'export' => false,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'multiple' => false,
            ],
            ['class' => 'yii\grid\SerialColumn'],
            //  'build_id',
            'build_name',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    var_dump($selectelement);
    end($this->params['breadcrumbs']);

    var_dump($this->params['breadcrumbs'][key($this->params['breadcrumbs']) - 1]['url']);
    var_dump(key(array_slice($this->params['breadcrumbs'], -1, 1, TRUE)));
// var_dump(array_pop(array_keys($this->params['breadcrumbs'])));
    ?>

</div>
