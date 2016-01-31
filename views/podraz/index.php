<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PodrazSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Podrazs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="podraz-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Podraz', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'podraz_id',
            'podraz_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
