<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Spismatmaterials */

$this->title = 'Create Spismatmaterials';
$this->params['breadcrumbs'][] = ['label' => 'Spismatmaterials', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spismatmaterials-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
