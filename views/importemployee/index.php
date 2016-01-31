<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ImportemployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Importemployees';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="importemployee-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Importemployee', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'importemployee_id',
            'importemployee_combination',
           /* 'id_build',
            'id_podraz',*/
            'idpodraz.podraz_name',
            'idbuild.build_name',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
