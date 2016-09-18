<?php
\Yii::$app->getView()->registerJsFile('js/freewall.js');
\Yii::$app->getView()->registerJsFile('js/fregatmainmenu.js');

use yii\helpers\Html;
use app\func\Proc;

$this->title = 'Справочники';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>

<div class="panel panel-<?= Yii::$app->params['panelStyle'] ?> menuplitka">
    <div class="panel-heading">Справочники</div>
    <div class="panel-body">
        <?php if (Yii::$app->user->can('FregatUserPermission')): ?>
            <div class="menublock">
                <?php if (Yii::$app->user->can('FregatUserPermission')): ?>
                    <div class="menubutton menubutton_activeanim mb_gray" id="mb_sp_matvid">
                        <span class="hoverspan"></span>
                        <div class="menubutton_cn"><i class="glyphicon glyphicon-credit-card"></i> Виды материальных
                            ценностей
                        </div>
                    </div>
                    <div class="menubutton menubutton_activeanim mb_gray" id="mb_sp_grupa">
                        <span class="hoverspan"></span>
                        <div class="menubutton_cn"><i class="glyphicon glyphicon-duplicate"></i> Группы материальных
                            ценностей
                        </div>
                    </div>
                    <div class="menubutton menubutton_activeanim mb_gray" id="mb_sp_organ">
                        <span class="hoverspan"></span>
                        <div class="menubutton_cn"><i class="glyphicon glyphicon-object-align-bottom"></i> Организации
                        </div>
                    </div>
                    <div class="menubutton menubutton_activeanim mb_gray" id="mb_sp_reason">
                        <span class="hoverspan"></span>
                        <div class="menubutton_cn"><i class="glyphicon glyphicon-book"></i> Шаблоны актов осмотра
                            материальной ценности
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('GlaukUserPermission') || Yii::$app->user->can('FregatUserPermission')): ?>
            <div class="menublock">
                <?php if (Yii::$app->user->can('EmployeeSpecEdit') || Yii::$app->user->can('FregatUserPermission')): ?>
                    <div class="menubutton menubutton_activeanim mb_gray" id="mb_sp_employee">
                        <span class="hoverspan"></span>
                        <div class="menubutton_cn"><i class="glyphicon glyphicon-user"></i> Сотрудники</div>
                    </div>
                    <?php if (Yii::$app->user->can('FregatUserPermission')): ?>
                        <div class="menubutton menubutton_activeanim mb_gray" id="mb_sp_dolzh">
                            <span class="hoverspan"></span>
                            <div class="menubutton_cn"><i class="glyphicon glyphicon-education"></i> Должности</div>
                        </div>
                        <div class="menubutton menubutton_activeanim mb_gray" id="mb_sp_podraz">
                            <span class="hoverspan"></span>
                            <div class="menubutton_cn"><i class="glyphicon glyphicon-briefcase"></i> Подразделения
                            </div>
                        </div>
                        <div class="menubutton menubutton_activeanim mb_gray" id="mb_sp_build">
                            <span class="hoverspan"></span>
                            <div class="menubutton_cn"><i class="glyphicon glyphicon-home"></i> Здания</div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('GlaukUserPermission')): ?>
            <div class="menublock">
                <?php if (Yii::$app->user->can('GlaukUserPermission')): ?>
                    <div class="menubutton menubutton_activeanim mb_gray" id="mb_sp_preparat">
                        <span class="hoverspan"></span>
                        <div class="menubutton_cn"><i class="glyphicon glyphicon-tint"></i> Препараты</div>
                    </div>
                    <div class="menubutton menubutton_activeanim mb_gray" id="mb_sp_classmkb">
                        <span class="hoverspan"></span>
                        <div class="menubutton_cn"><i class="glyphicon glyphicon-heart-empty"></i> МКБ-10</div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
