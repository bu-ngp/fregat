<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Spismat */

$this->title = 'Update Spismat: ' . $model->spismat_id;
$this->params['breadcrumbs'][] = ['label' => 'Spismats', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->spismat_id, 'url' => ['view', 'id' => $model->spismat_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="spismat-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
