<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Glauk\Glprep */

$this->title = 'Create Glprep';
$this->params['breadcrumbs'][] = ['label' => 'Glpreps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="glprep-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
