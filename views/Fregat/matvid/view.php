<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Matvid */

$this->title = $model->matvid_id;
$this->params['breadcrumbs'][] = ['label' => 'Matvids', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="matvid-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->matvid_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->matvid_id], [
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
            'matvid_id',
            'matvid_name',
        ],
    ]) ?>

</div>