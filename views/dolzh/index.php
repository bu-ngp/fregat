<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DolzhSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dolzhs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dolzh-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dolzh', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'dolzh_id',
            'dolzh_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
