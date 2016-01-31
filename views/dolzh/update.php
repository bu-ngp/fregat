<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Dolzh */

$this->title = 'Update Dolzh: ' . ' ' . $model->dolzh_id;
$this->params['breadcrumbs'][] = ['label' => 'Dolzhs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->dolzh_id, 'url' => ['view', 'id' => $model->dolzh_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dolzh-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
