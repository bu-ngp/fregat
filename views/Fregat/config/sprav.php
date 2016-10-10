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
                        <div class="menubutton_cn">Виды материальных
                            ценностей
                        </div>
                        <i class="glyphicon glyphicon-credit-card"></i>
                    </div>
                    <div class="menubutton menubutton_activeanim mb_gray" id="mb_sp_grupa">
                        <span class="hoverspan"></span>
                        <div class="menubutton_cn">Группы материальных
                            ценностей
                        </div>
                        <i class="glyphicon glyphicon-duplicate"></i>
                    </div>
                    <div class="menubutton menubutton_activeanim mb_gray" id="mb_sp_organ">
                        <span class="hoverspan"></span>
                        <div class="menubutton_cn">Организации
                        </div>
                        <i class="glyphicon glyphicon-object-align-bottom"></i>
                    </div>
                    <div class="menubutton menubutton_activeanim mb_gray" id="mb_sp_reason">
                        <span class="hoverspan"></span>
                        <div class="menubutton_cn">Шаблоны актов осмотра
                            материальной ценности
                        </div>
                        <i class="glyphicon glyphicon-book"></i>
                    </div>
                    <div class="menubutton menubutton_activeanim mb_gray" id="mb_sp_schetuchet">
                        <span class="hoverspan"></span>
                        <div class="menubutton_cn">Счета учета</div>
                        <i class="glyphicon glyphicon-folder-open"></i>
                    </div>
                    <div class="menubutton menubutton_activeanim mb_gray" id="mb_sp_docfiles">
                        <span class="hoverspan"></span>
                        <div class="menubutton_cn">Загруженные документы</div>
                        <i class="glyphicon glyphicon-file"></i>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('GlaukUserPermission') || Yii::$app->user->can('FregatUserPermission')): ?>
            <div class="menublock">
                <?php if (Yii::$app->user->can('EmployeeSpecEdit') || Yii::$app->user->can('FregatUserPermission')): ?>
                    <div class="menubutton menubutton_activeanim mb_gray" id="mb_sp_employee">
                        <span class="hoverspan"></span>
                        <div class="menubutton_cn">Сотрудники</div>
                        <i class="glyphicon glyphicon-user"></i>
                    </div>
                    <?php if (Yii::$app->user->can('FregatUserPermission')): ?>
                        <div class="menubutton menubutton_activeanim mb_gray" id="mb_sp_dolzh">
                            <span class="hoverspan"></span>
                            <div class="menubutton_cn">Должности</div>
                            <i class="glyphicon glyphicon-education"></i>
                        </div>
                        <div class="menubutton menubutton_activeanim mb_gray" id="mb_sp_podraz">
                            <span class="hoverspan"></span>
                            <div class="menubutton_cn">Подразделения
                            </div>
                            <i class="glyphicon glyphicon-briefcase"></i>
                        </div>
                        <div class="menubutton menubutton_activeanim mb_gray" id="mb_sp_build">
                            <span class="hoverspan"></span>
                            <div class="menubutton_cn">Здания</div>
                            <i class="glyphicon glyphicon-home"></i>
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
                        <div class="menubutton_cn">Препараты</div>
                        <i class="glyphicon glyphicon-tint"></i>
                    </div>
                    <div class="menubutton menubutton_activeanim mb_gray" id="mb_sp_classmkb">
                        <span class="hoverspan"></span>
                        <div class="menubutton_cn">МКБ-10</div>
                        <i class="glyphicon glyphicon-heart-empty"></i>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
