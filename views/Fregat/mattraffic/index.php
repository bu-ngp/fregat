<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\MattrafficSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mattraffics';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mattraffic-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Mattraffic', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'mattraffic_id',
            'mattraffic_date',
            'mattraffic_number',
            'id_material',
            'id_mol',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
