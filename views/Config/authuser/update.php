<?php

use yii\helpers\Html;
use app\func\Proc;

$this->title = 'Обновить пользователя';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
            'model' => [$model, $Authassignment],
        ]);
?>
<div class="authuser-update">
    <div class="panel panel-info">
        <div class="panel-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ])
            ?>
        </div>
    </div>
</div>
