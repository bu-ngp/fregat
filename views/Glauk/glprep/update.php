<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Glauk\Glprep */

$this->title = 'Update Glprep: ' . $model->glprep_id;
$this->params['breadcrumbs'][] = ['label' => 'Glpreps', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->glprep_id, 'url' => ['view', 'id' => $model->glprep_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="glprep-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
