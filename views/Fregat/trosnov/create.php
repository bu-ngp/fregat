<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fregat\TrOsnov */

$this->title = 'Create Tr Osnov';
$this->params['breadcrumbs'][] = ['label' => 'Tr Osnovs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-osnov-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
