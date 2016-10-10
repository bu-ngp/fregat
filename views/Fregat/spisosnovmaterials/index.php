<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\SpisosnovmaterialsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Spisosnovmaterials';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spisosnovmaterials-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Spisosnovmaterials', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'spisosnovmaterials_id',
            'id_mattraffic',
            'id_spisosnovakt',
            'spisosnovmaterials_number',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
