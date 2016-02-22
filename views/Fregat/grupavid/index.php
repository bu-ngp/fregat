<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\GrupavidSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Grupavids';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grupavid-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Grupavid', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'grupavid_id',
            'grupavid_main',
            'id_grupa',
            'id_matvid',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
