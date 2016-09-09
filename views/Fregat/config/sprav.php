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
                <li>
                    <?= Html::a('<i class="glyphicon glyphicon-object-align-bottom"></i> Организации', ['//Fregat/organ/index'], ['class' => 'btn btn-default']) ?>
                </li>
                <li>
                    <?= Html::a('<i class="glyphicon glyphicon-book"></i> Шаблоны актов осмотра материальной ценности', ['//Fregat/reason/index'], ['class' => 'btn btn-default']) ?>
                </li>
            <?php endif; ?>
            <?php if (Yii::$app->user->can('GlaukUserPermission')): ?>
                <?php if (Yii::$app->user->can('EmployeeSpecEdit')): ?>
                    <li>
                        <?= Html::a('<i class="glyphicon glyphicon-user"></i> Сотрудники', ['//Config/authuser/index', 'emp' => true], ['class' => 'btn btn-default']) ?>
                    </li>
                <?php endif; ?>
                <li>
                    <?= Html::a('<i class="glyphicon glyphicon-tint"></i> Препараты', ['//Base/preparat/index'], ['class' => 'btn btn-default']) ?>
                </li>
                <li>
                    <?= Html::a('<i class="glyphicon glyphicon-heart-empty"></i> МКБ-10', ['//Base/classmkb/index'], ['class' => 'btn btn-default']) ?>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<div class="form-group">
    <div class="panel panel-default">
        <div class="panel-heading">
            <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Назад', Proc::GetPreviousURLBreadcrumbsFromSession(), ['class' => 'btn btn-info']) ?>
        </div>
    </div>
</div>