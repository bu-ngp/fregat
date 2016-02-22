<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Mattraffic */

$this->title = 'Update Mattraffic: ' . ' ' . $model->mattraffic_id;
$this->params['breadcrumbs'][] = ['label' => 'Mattraffics', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mattraffic_id, 'url' => ['view', 'id' => $model->mattraffic_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mattraffic-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
