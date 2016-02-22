<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Grupavid */

$this->title = 'Update Grupavid: ' . ' ' . $model->grupavid_id;
$this->params['breadcrumbs'][] = ['label' => 'Grupavids', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->grupavid_id, 'url' => ['view', 'id' => $model->grupavid_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="grupavid-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
