<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BuildSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Builds';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="build-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Build', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'build_id',
            'build_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
