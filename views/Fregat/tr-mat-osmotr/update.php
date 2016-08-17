<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\TrMatOsmotr */

$this->title = 'Update Tr Mat Osmotr: ' . $model->tr_mat_osmotr_id;
$this->params['breadcrumbs'][] = ['label' => 'Tr Mat Osmotrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tr_mat_osmotr_id, 'url' => ['view', 'id' => $model->tr_mat_osmotr_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tr-mat-osmotr-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
