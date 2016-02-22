<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Grupavid */

$this->title = $model->grupavid_id;
$this->params['breadcrumbs'][] = ['label' => 'Grupavids', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grupavid-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->grupavid_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->grupavid_id], [
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
            'grupavid_id',
            'grupavid_main',
            'id_grupa',
            'id_matvid',
        ],
    ]) ?>

</div>
