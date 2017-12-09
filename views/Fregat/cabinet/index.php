<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\CabinetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cabinets';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cabinet-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Cabinet', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'cabinet_id',
            'id_build',
            'cabinet_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
