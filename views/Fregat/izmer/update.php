<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Izmer */

$this->title = 'Update Izmer: ' . ' ' . $model->izmer_id;
$this->params['breadcrumbs'][] = ['label' => 'Izmers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->izmer_id, 'url' => ['view', 'id' => $model->izmer_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="izmer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
