<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fregat\TrMatOsmotr */

$this->title = 'Create Tr Mat Osmotr';
$this->params['breadcrumbs'][] = ['label' => 'Tr Mat Osmotrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-mat-osmotr-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
