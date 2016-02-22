<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Grupavid */

$this->title = 'Create Grupavid';
$this->params['breadcrumbs'][] = ['label' => 'Grupavids', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grupavid-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
