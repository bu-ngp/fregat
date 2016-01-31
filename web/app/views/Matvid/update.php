<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Matvid */

$this->title = 'Update Matvid: ' . ' ' . $model->matvid_id;
$this->params['breadcrumbs'][] = ['label' => 'Matvids', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->matvid_id, 'url' => ['view', 'id' => $model->matvid_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="matvid-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
