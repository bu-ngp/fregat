<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Base\Classmkb */

$this->title = 'Update Classmkb: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Classmkbs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="classmkb-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
