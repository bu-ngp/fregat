<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\RramatDocfiles */

$this->title = 'Update Rramat Docfiles: ' . $model->rramat_docfiles_id;
$this->params['breadcrumbs'][] = ['label' => 'Rramat Docfiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->rramat_docfiles_id, 'url' => ['view', 'id' => $model->rramat_docfiles_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rramat-docfiles-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
