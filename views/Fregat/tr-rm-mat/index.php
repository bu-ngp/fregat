<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\TrRmMatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tr Rm Mats';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-rm-mat-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tr Rm Mat', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'tr_rm_mat_id',
            'id_removeakt',
            'id_mattraffic',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
