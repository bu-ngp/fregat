<?php
\Yii::$app->getView()->registerJsFile('@web/js/freewall.js');
\Yii::$app->getView()->registerJsFile('@web/js/fregatmainmenu.js');

use yii\helpers\Html;
use app\func\Proc;

$this->title = 'Импорт данных';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>

<div class="panel panel-<?= Yii::$app->params['panelStyle'] ?> menuplitka">
    <div class="panel-heading">Импорт данных</div>
    <div class="panel-body">
        <div class="menublock">
            <?php if (Yii::$app->user->can('FregatImport')): ?>
                <div class="menubutton menubutton_activeanim mb_red" id="mb_fregatimp_conf_employee">
                    <span class="hoverspan"></span>
                    <div class="menubutton_cn">Настройка импорта сотрудников
                    </div>
                    <i class="glyphicon glyphicon-user"></i>
                </div>
                <div class="menubutton menubutton_activeanim mb_red" id="mb_fregatimp_conf_material">
                    <span class="hoverspan"></span>
                    <div class="menubutton_cn">Настройка импорта материальных
                        ценностей
                    </div>
                    <i class="glyphicon glyphicon-gift"></i>
                </div>
                <div class="menubutton menubutton_activeanim mb_yellow" id="mb_fregatimp_reports">
                    <span class="hoverspan"></span>
                    <div class="menubutton_cn">Сервис</div>
                    <i class="glyphicon glyphicon-inbox"></i>
                </div>
                <div class="menubutton menubutton_activeanim mb_gray" id="mb_fregatimp_conf">
                    <span class="hoverspan"></span>
                    <div class="menubutton_cn">Общие настройки</div>
                    <i class="glyphicon glyphicon-cog"></i>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

