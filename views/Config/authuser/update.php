<?php

use yii\helpers\Html;
use app\func\Proc;

$this->title = 'Обновить пользователя';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this,[
    'model' => [$model, $Authassignment],    
]);
?>
<div class="authuser-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>
