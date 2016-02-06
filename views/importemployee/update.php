<?php

use yii\helpers\Html;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $model app\models\Importemployee */

$this->title = 'Обновить словосочетание';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="importemployee-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ])
    ?>

</div>
