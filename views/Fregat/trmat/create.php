<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fregat\TrMat */

$this->title = 'Create Tr Mat';
$this->params['breadcrumbs'][] = ['label' => 'Tr Mats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-mat-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
