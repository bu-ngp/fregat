<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Build */

$this->title = 'Update Build: ' . ' ' . $model->build_id;
$this->params['breadcrumbs'][] = ['label' => 'Builds', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->build_id, 'url' => ['view', 'id' => $model->build_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="build-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
