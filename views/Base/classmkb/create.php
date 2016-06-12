<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Base\Classmkb */

$this->title = 'Create Classmkb';
$this->params['breadcrumbs'][] = ['label' => 'Classmkbs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="classmkb-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
