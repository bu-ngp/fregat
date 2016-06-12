<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Base\Preparat */

$this->title = 'Create Preparat';
$this->params['breadcrumbs'][] = ['label' => 'Preparats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="preparat-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
