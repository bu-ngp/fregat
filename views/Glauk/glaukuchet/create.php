<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Glauk\Glaukuchet */

$this->title = 'Create Glaukuchet';
$this->params['breadcrumbs'][] = ['label' => 'Glaukuchets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="glaukuchet-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
