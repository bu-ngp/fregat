<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ImpemployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Impemployees';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="impemployee-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Impemployee', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'impemployee_id',
            'id_importemployee',
            'id_employee',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
