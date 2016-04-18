<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Osmotrakt */

$this->title = $model->osmotrakt_id;
$this->params['breadcrumbs'][] = ['label' => 'Osmotrakts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="osmotrakt-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->osmotrakt_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->osmotrakt_id], [
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
            'osmotrakt_id',
            'osmotrakt_comment',
            'id_reason',
            'id_user',
            'id_master',
            'id_mattraffic',
        ],
    ]) ?>

</div>
