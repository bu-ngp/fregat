<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Podraz */

$this->title = 'Update Podraz: ' . ' ' . $model->podraz_id;
$this->params['breadcrumbs'][] = ['label' => 'Podrazs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->podraz_id, 'url' => ['view', 'id' => $model->podraz_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="podraz-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
