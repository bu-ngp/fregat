<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\Import\LogreportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Logreports';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logreport-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Logreport', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'logreport_id',
            'logreport_date',
            'logreport_errors',
            'logreport_updates',
            'logreport_additions',
            // 'logreport_amount',
            // 'logreport_missed',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
