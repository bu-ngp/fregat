<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Impemployee */

$this->title = 'Update Impemployee: ' . ' ' . $model->impemployee_id;
$this->params['breadcrumbs'][] = ['label' => 'Impemployees', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->impemployee_id, 'url' => ['view', 'id' => $model->impemployee_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="impemployee-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
