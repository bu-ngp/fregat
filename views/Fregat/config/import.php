<?php
\Yii::$app->getView()->registerJsFile('js/freewall.js');
\Yii::$app->getView()->registerJsFile('js/fregatmainmenu.js');

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
                    <div class="menubutton_cn"><i class="glyphicon glyphicon-user"></i> Настройка импорта сотрудников
                    </div>
                </div>
                <div class="menubutton menubutton_activeanim mb_red" id="mb_fregatimp_conf_material">
                    <span class="hoverspan"></span>
                    <div class="menubutton_cn"><i class="glyphicon glyphicon-gift"></i> Настройка импорта материальных
                        ценностей
                    </div>
                </div>
                <div class="menubutton menubutton_activeanim mb_yellow" id="mb_fregatimp_reports">
                    <span class="hoverspan"></span>
                    <div class="menubutton_cn"><i class="glyphicon glyphicon-inbox"></i> Отчеты</div>
                </div>
                <div class="menubutton menubutton_activeanim mb_gray" id="mb_fregatimp_conf">
                    <span class="hoverspan"></span>
                    <div class="menubutton_cn"><i class="glyphicon glyphicon-cog"></i> Общие настройки</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

