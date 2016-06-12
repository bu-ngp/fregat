<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Glauk\Glaukuchet */

$this->title = 'Update Glaukuchet: ' . $model->glaukuchet_id;
$this->params['breadcrumbs'][] = ['label' => 'Glaukuchets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->glaukuchet_id, 'url' => ['view', 'id' => $model->glaukuchet_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="glaukuchet-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
