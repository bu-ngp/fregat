<?php

use yii\helpers\Html;
use app\func\Proc;
use yii\helpers\Url;

$this->title = 'Основные';

$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
            'addfirst' => [
                'label' => 'Настройки портала',
                'url' => Url::toRoute('Config/config/index'),
            ],
            'clearbefore' => true,
        ]);
?>

<div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
    <div class="panel-heading">Настройки портала</div>
    <div class="panel-body">     
        <ul class="nav nav-pills nav-stacked">
            <?php if (Yii::$app->user->can('UserEdit')): ?>
                <li>
                    <?= Html::a('<i class="glyphicon glyphicon-user"></i> Менеджер пользователей', ['Config/authuser/index'], ['class' => 'btn btn-default']) ?>
                </li>
            <?php endif; ?>
            <?php if (Yii::$app->user->can('RoleEdit')): ?>
                <li>
                    <?= Html::a('<i class="glyphicon glyphicon-align-justify"></i> Менеджер ролей', ['Config/authitem/index'], ['class' => 'btn btn-default']) ?>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>