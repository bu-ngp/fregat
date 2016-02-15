<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Podraz */

$this->title = $model->podraz_id;
$this->params['breadcrumbs'][] = ['label' => 'Podrazs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="podraz-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->podraz_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->podraz_id], [
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
            'podraz_id',
            'podraz_name',
        ],
    ]) ?>

</div>
