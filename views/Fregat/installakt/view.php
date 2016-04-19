<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Installakt */

$this->title = $model->installakt_id;
$this->params['breadcrumbs'][] = ['label' => 'Installakts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="installakt-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->installakt_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->installakt_id], [
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
            'installakt_id',
            'installakt_date',
            'id_installer',
        ],
    ]) ?>

</div>
