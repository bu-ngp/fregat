<?php
use yii\helpers\Html;
use app\func\Proc;


$this->title = 'Импорт данных';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);

echo Html::a('Настройка импорта сотрудников', ['//Fregat/importemployee/index'], ['class' => 'btn btn-primary']);
