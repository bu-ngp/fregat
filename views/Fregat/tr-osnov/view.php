<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\TrOsnov */

$this->title = $model->tr_osnov_id;
$this->params['breadcrumbs'][] = ['label' => 'Tr Osnovs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-osnov-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->tr_osnov_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->tr_osnov_id], [
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
            'tr_osnov_id',
            'tr_osnov_kab',
            'id_installakt',
            'id_mattraffic',
        ],
    ]) ?>

</div>
