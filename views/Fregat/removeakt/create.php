<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Removeakt */

$this->title = 'Create Removeakt';
$this->params['breadcrumbs'][] = ['label' => 'Removeakts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="removeakt-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
