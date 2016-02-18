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

echo Html::a('Менеджер пользователей', ['Config/authuser/index'], ['class' => 'btn btn-primary']);