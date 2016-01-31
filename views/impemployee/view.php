<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Impemployee */

$this->title = $model->impemployee_id;
$this->params['breadcrumbs'][] = ['label' => 'Impemployees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="impemployee-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->impemployee_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->impemployee_id], [
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
            'impemployee_id',
            'id_importemployee',
            'id_employee',
        ],
    ]) ?>

</div>
