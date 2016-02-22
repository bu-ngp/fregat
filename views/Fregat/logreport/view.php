<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Import\Logreport */

$this->title = $model->logreport_id;
$this->params['breadcrumbs'][] = ['label' => 'Logreports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logreport-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->logreport_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->logreport_id], [
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
            'logreport_id',
            'logreport_date',
            'logreport_errors',
            'logreport_updates',
            'logreport_additions',
            'logreport_amount',
            'logreport_missed',
        ],
    ]) ?>

</div>
