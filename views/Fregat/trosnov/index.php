<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\TrOsnovSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tr Osnovs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-osnov-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tr Osnov', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'tr_osnov_id',
            'tr_osnov_kab',
            'id_installakt',
            'id_mattraffic',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
