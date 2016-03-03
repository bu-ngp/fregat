<?php

use yii\helpers\Html;
use app\func\Proc;

$this->title = 'Справочники';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>

<div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
    <div class="panel-heading">Справочники</div>
    <div class="panel-body">     
        <ul class="nav nav-pills nav-stacked">
            <?php if (Yii::$app->user->can('FregatUserPermission')): ?>
                <li>
                    <?= Html::a('<i class="glyphicon glyphicon-user"></i> Сотрудники', ['//Config/authuser/index', 'emp' => true], ['class' => 'btn btn-default']) ?>
                </li>
                <li>
                    <?= Html::a('<i class="glyphicon glyphicon-education"></i> Должности', ['//Fregat/dolzh/index'], ['class' => 'btn btn-default']) ?>
                </li>
                <li>
                    <?= Html::a('<i class="glyphicon glyphicon-briefcase"></i> Подразделения', ['//Fregat/podraz/index'], ['class' => 'btn btn-default']) ?>
                </li>
                <li>
                    <?= Html::a('<i class="glyphicon glyphicon-home"></i> Здания', ['//Fregat/build/index'], ['class' => 'btn btn-default']) ?>
                </li>
                <li>
                    <?= Html::a('<i class="glyphicon glyphicon-credit-card"></i> Виды материальных ценностей', ['//Fregat/matvid/index'], ['class' => 'btn btn-default']) ?>
                </li>
                <li>
                    <?= Html::a('<i class="glyphicon glyphicon-duplicate"></i> Группы материальных ценностей', ['//Fregat/grupa/index'], ['class' => 'btn btn-default']) ?>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
