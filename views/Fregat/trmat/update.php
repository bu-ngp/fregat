<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\TrMat */

$this->title = 'Update Tr Mat: ' . $model->tr_mat_id;
$this->params['breadcrumbs'][] = ['label' => 'Tr Mats', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tr_mat_id, 'url' => ['view', 'id' => $model->tr_mat_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tr-mat-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
