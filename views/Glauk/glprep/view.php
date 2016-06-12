<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Glauk\Glprep */

$this->title = $model->glprep_id;
$this->params['breadcrumbs'][] = ['label' => 'Glpreps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="glprep-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->glprep_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->glprep_id], [
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
            'glprep_id',
            'id_glaukuchet',
            'id_preparat',
        ],
    ]) ?>

</div>
