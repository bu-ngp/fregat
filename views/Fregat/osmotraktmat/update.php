<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Osmotraktmat */

$this->title = 'Update Osmotraktmat: ' . $model->osmotraktmat_id;
$this->params['breadcrumbs'][] = ['label' => 'Osmotraktmats', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->osmotraktmat_id, 'url' => ['view', 'id' => $model->osmotraktmat_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="osmotraktmat-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
