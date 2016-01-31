<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Importemployee */

$this->title = $model->importemployee_id;
$this->params['breadcrumbs'][] = ['label' => 'Importemployees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="importemployee-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->importemployee_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->importemployee_id], [
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
            'importemployee_id',
            'importemployee_combination',
            'id_build',
            'id_podraz',
        ],
    ]) ?>

</div>
