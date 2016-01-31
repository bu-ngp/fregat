<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Podraz */

$this->title = 'Create Podraz';
$this->params['breadcrumbs'][] = ['label' => 'Podrazs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="podraz-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
