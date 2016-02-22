<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Izmer */

$this->title = $model->izmer_id;
$this->params['breadcrumbs'][] = ['label' => 'Izmers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="izmer-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->izmer_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->izmer_id], [
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
            'izmer_id',
            'izmer_name',
        ],
    ]) ?>

</div>
