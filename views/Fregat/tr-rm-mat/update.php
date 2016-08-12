<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\TrRmMat */

$this->title = 'Update Tr Rm Mat: ' . $model->tr_rm_mat_id;
$this->params['breadcrumbs'][] = ['label' => 'Tr Rm Mats', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tr_rm_mat_id, 'url' => ['view', 'id' => $model->tr_rm_mat_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tr-rm-mat-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
