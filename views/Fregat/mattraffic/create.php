<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Mattraffic */

$this->title = 'Create Mattraffic';
$this->params['breadcrumbs'][] = ['label' => 'Mattraffics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mattraffic-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
