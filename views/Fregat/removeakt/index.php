<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\RemoveaktSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Removeakts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="removeakt-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Removeakt', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'removeakt_id',
            'removeakt_date',
            'id_remover',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
