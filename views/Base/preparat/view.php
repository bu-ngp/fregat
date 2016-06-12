<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Base\Preparat */

$this->title = $model->preparat_id;
$this->params['breadcrumbs'][] = ['label' => 'Preparats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="preparat-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->preparat_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->preparat_id], [
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
            'preparat_id',
            'preparat_name',
        ],
    ]) ?>

</div>
