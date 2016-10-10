<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\RramatDocfilesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rramat Docfiles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rramat-docfiles-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Rramat Docfiles', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'rramat_docfiles_id',
            'id_docfiles',
            'id_recoveryrecieveaktmat',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
