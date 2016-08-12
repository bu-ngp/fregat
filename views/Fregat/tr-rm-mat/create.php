<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fregat\TrRmMat */

$this->title = 'Create Tr Rm Mat';
$this->params['breadcrumbs'][] = ['label' => 'Tr Rm Mats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-rm-mat-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
