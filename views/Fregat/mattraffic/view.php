<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Mattraffic */

$this->title = $model->mattraffic_id;
$this->params['breadcrumbs'][] = ['label' => 'Mattraffics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mattraffic-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->mattraffic_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->mattraffic_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'mattraffic_id',
            'mattraffic_date',
            'mattraffic_number',
            'id_material',
            'id_mol',
        ],
    ]) ?>

</div>
