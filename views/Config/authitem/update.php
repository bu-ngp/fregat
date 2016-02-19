<?php

use yii\helpers\Html;
use app\func\Proc;

$this->title = 'Обновить авторизационную единицу';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this,[
    'model' => [$model, $Authitemchild],    
]);
?>
<div class="authitem-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>
