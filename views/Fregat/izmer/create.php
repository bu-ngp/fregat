<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Izmer */

$this->title = 'Create Izmer';
$this->params['breadcrumbs'][] = ['label' => 'Izmers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="izmer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
