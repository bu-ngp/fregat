<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Spismatmaterials */

$this->title = 'Update Spismatmaterials: ' . $model->spismatmaterials_id;
$this->params['breadcrumbs'][] = ['label' => 'Spismatmaterials', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->spismatmaterials_id, 'url' => ['view', 'id' => $model->spismatmaterials_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="spismatmaterials-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
