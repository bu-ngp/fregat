<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Osmotrakt */

$this->title = 'Update Osmotrakt: ' . $model->osmotrakt_id;
$this->params['breadcrumbs'][] = ['label' => 'Osmotrakts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->osmotrakt_id, 'url' => ['view', 'id' => $model->osmotrakt_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="osmotrakt-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
