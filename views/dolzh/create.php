<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Dolzh */

$this->title = 'Create Dolzh';
$this->params['breadcrumbs'][] = ['label' => 'Dolzhs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dolzh-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
