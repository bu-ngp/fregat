<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Importemployee */

$this->title = 'Create Importemployee';
$this->params['breadcrumbs'][] = ['label' => 'Importemployees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="importemployee-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
