<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MatvidSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Matvids';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="matvid-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Matvid', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'matvid_id',
            'matvid_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
