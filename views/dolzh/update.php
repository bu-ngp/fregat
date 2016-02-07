<?php

use yii\helpers\Html;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $model app\models\Dolzh */

$this->title = 'Обновить должность';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="dolzh-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
