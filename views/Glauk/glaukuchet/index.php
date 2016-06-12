<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Glauk\GlaukuchetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Glaukuchets';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="glaukuchet-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Glaukuchet', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'glaukuchet_id',
            'glaukuchet_uchetbegin',
            'glaukuchet_detect',
            'glaukuchet_deregdate',
            'glaukuchet_deregreason',
            // 'glaukuchet_stage',
            // 'glaukuchet_operdate',
            // 'glaukuchet_rlocat',
            // 'glaukuchet_invalid',
            // 'glaukuchet_lastvisit',
            // 'glaukuchet_lastmetabol',
            // 'id_patient',
            // 'id_employee',
            // 'id_class_mkb',
            // 'glaukuchet_comment',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
