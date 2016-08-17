<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\OsmotraktmatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Osmotraktmats';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="osmotraktmat-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Osmotraktmat', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'osmotraktmat_id',
            'osmotraktmat_comment',
            'osmotraktmat_date',
            'id_reason',
            'id_tr_mat',
            // 'id_master',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
