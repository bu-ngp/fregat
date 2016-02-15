<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Dolzh */

$this->title = $model->dolzh_id;
$this->params['breadcrumbs'][] = ['label' => 'Dolzhs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dolzh-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->dolzh_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->dolzh_id], [
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
            'dolzh_id',
            'dolzh_name',
        ],
    ]) ?>

</div>
