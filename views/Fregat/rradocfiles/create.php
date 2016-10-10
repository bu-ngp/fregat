<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fregat\RraDocfiles */

$this->title = 'Create Rra Docfiles';
$this->params['breadcrumbs'][] = ['label' => 'Rra Docfiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rra-docfiles-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
