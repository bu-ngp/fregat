<?php

use yii\helpers\Html;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Importemployee */

$this->title = 'Создать словосочетание';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this,[
    'model' => $model,
]);
?>
<div class="importemployee-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    
</div>
