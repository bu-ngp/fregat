<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\TrMatOsmotrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tr Mat Osmotrs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-mat-osmotr-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tr Mat Osmotr', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'tr_mat_osmotr_id',
            'id_tr_mat',
            'id_osmotraktmat',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
