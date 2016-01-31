<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Matvid */

$this->title = 'Create Matvid';
$this->params['breadcrumbs'][] = ['label' => 'Matvids', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="matvid-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
