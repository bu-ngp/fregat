<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\TrMat */

$this->title = $model->tr_mat_id;
$this->params['breadcrumbs'][] = ['label' => 'Tr Mats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-mat-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->tr_mat_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->tr_mat_id], [
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
            'tr_mat_id',
            'id_installakt',
            'id_mattraffic',
            'id_parent',
        ],
    ]) ?>

</div>
