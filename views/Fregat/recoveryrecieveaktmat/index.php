<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\RecoveryrecieveaktmatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Recoveryrecieveaktmats';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recoveryrecieveaktmat-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Recoveryrecieveaktmat', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'recoveryrecieveaktmat_id',
            'recoveryrecieveaktmat_result',
            'recoveryrecieveaktmat_repaired',
            'recoveryrecieveaktmat_date',
            'id_recoverysendakt',
            // 'id_tr_mat_osmotr',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
