<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\TrOsnov */

$this->title = 'Update Tr Osnov: ' . $model->tr_osnov_id;
$this->params['breadcrumbs'][] = ['label' => 'Tr Osnovs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tr_osnov_id, 'url' => ['view', 'id' => $model->tr_osnov_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tr-osnov-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
