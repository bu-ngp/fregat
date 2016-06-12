<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Base\Classmkb */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Classmkbs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="classmkb-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'name',
            'code',
            'parent_id',
            'parent_code',
            'node_count',
            'additional_info:ntext',
        ],
    ]) ?>

</div>
