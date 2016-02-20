<?php

use yii\helpers\Html;
use app\func\Proc;

$this->title = 'Изменить пароль пользователя';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
            'model' => $model,
        ]);
?>
<div class="authuser-changepassword">
    <div class="panel panel-info">
        <div class="panel-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
            ])
            ?>
        </div>
    </div>
</div>
