<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Impemployee */

$this->title = 'Create Impemployee';
$this->params['breadcrumbs'][] = ['label' => 'Impemployees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="impemployee-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
