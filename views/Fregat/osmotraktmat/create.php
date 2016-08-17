<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Osmotraktmat */

$this->title = 'Create Osmotraktmat';
$this->params['breadcrumbs'][] = ['label' => 'Osmotraktmats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="osmotraktmat-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
