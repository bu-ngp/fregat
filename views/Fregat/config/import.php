<?php

use yii\helpers\Html;
use app\func\Proc;

$this->title = 'Импорт данных';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>

<div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
    <div class="panel-heading">Импорт данных</div>
    <div class="panel-body">     
        <ul class="nav nav-pills nav-stacked">
            <?php if (Yii::$app->user->can('FregatImport')): ?>
                <li>
                    <?= Html::a('<i class="glyphicon glyphicon-user"></i> Настройка импорта сотрудников', ['//Fregat/importemployee/index'], ['class' => 'btn btn-default']) ?>
                </li>
                <li>
                    <?= Html::a('<i class="glyphicon glyphicon-gift"></i> Настройка импорта материальных ценностей', ['//Fregat/importmaterial/index'], ['class' => 'btn btn-default']) ?>
                </li>
                <li>
                    <?= Html::a('<i class="glyphicon glyphicon-inbox"></i> Отчеты', ['//Fregat/logreport/index'], ['class' => 'btn btn-default']) ?>
                </li>
                <li>
                    <?= Html::a('<i class="glyphicon glyphicon-cog"></i> Общие настройки', ['//Fregat/importconfig/update'], ['class' => 'btn btn-default']) ?>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
