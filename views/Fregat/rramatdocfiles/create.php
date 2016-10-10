<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fregat\RramatDocfiles */

$this->title = 'Create Rramat Docfiles';
$this->params['breadcrumbs'][] = ['label' => 'Rramat Docfiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rramat-docfiles-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
