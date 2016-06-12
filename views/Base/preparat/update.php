<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Base\Preparat */

$this->title = 'Update Preparat: ' . $model->preparat_id;
$this->params['breadcrumbs'][] = ['label' => 'Preparats', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->preparat_id, 'url' => ['view', 'id' => $model->preparat_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="preparat-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
