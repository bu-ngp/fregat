<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\RecoveryrecieveaktSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Recoveryrecieveakts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recoveryrecieveakt-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Recoveryrecieveakt', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'recoveryrecieveakt_id',
            'id_osmotrakt',
            'id_recoverysendakt',
            'recoveryrecieveakt_result',
            'recoveryrecieveakt_repaired',
            // 'recoveryrecieveakt_date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
