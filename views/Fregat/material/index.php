<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\MaterialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Materials';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="material-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Material', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'material_id',
            'material_name',
            'material_name1c',
            'material_1c',
            'material_inv',
            // 'material_serial',
            // 'material_release',
            // 'material_number',
            // 'material_price',
            // 'material_tip',
            // 'material_writeoff',
            // 'id_matvid',
            // 'id_izmer',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
