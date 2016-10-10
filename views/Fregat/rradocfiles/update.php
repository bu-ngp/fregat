<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\RraDocfiles */

$this->title = 'Update Rra Docfiles: ' . $model->rra_docfiles_id;
$this->params['breadcrumbs'][] = ['label' => 'Rra Docfiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->rra_docfiles_id, 'url' => ['view', 'id' => $model->rra_docfiles_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rra-docfiles-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
