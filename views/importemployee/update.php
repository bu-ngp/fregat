<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Importemployee */

$this->title = 'Update Importemployee: ' . ' ' . $model->importemployee_id;
$this->params['breadcrumbs'][] = ['label' => 'Importemployees', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->importemployee_id, 'url' => ['view', 'id' => $model->importemployee_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="importemployee-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
