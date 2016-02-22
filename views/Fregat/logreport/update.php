<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Import\Logreport */

$this->title = 'Update Logreport: ' . ' ' . $model->logreport_id;
$this->params['breadcrumbs'][] = ['label' => 'Logreports', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->logreport_id, 'url' => ['view', 'id' => $model->logreport_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="logreport-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
