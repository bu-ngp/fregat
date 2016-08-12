<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Removeakt */

$this->title = 'Update Removeakt: ' . $model->removeakt_id;
$this->params['breadcrumbs'][] = ['label' => 'Removeakts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->removeakt_id, 'url' => ['view', 'id' => $model->removeakt_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="removeakt-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
