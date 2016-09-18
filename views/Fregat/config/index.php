<?php
\Yii::$app->getView()->registerJsFile('js/freewall.js');
\Yii::$app->getView()->registerJsFile('js/fregatmainmenu.js');

use yii\helpers\Html;
use app\func\Proc;
use yii\helpers\Url;

$this->title = 'Настройки';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
    'addfirst' => [
        'label' => 'Фрегат',
        'url' => Url::toRoute('Fregat/fregat/mainmenu'),
    ],
    'clearbefore' => true,
]);
?>

<div class="panel panel-<?= Yii::$app->params['panelStyle'] ?> menuplitka">
    <div class="panel-heading">Настройки системы "Фрегат"</div>
    <div class="panel-body">
        <div class="menublock">
            <?php if (Yii::$app->user->can('FregatImport')): ?>
                <div class="menubutton menubutton_activeanim mb_yellow" id="mb_fregatimport">
                    <span class="hoverspan"></span>
                    <div class="menubutton_cn"><i class="glyphicon glyphicon-import"></i> Импорт данных</div>
                </div>
            <?php endif; ?>
            <?php if (Yii::$app->user->can('FregatUserPermission')): ?>
                <div class="menubutton menubutton_activeanim mb_blue" id="mb_fregatsprav">
                    <span class="hoverspan"></span>
                    <div class="menubutton_cn"><i class="glyphicon glyphicon-list-alt"></i> Справочники</div>
                </div>
            <?php endif; ?>
            <?php if (Yii::$app->user->can('FregatConfig')): ?>
                <div class="menubutton menubutton_activeanim mb_gray" id="mb_fregatconfig">
                    <span class="hoverspan"></span>
                    <div class="menubutton_cn"><i class="glyphicon glyphicon-tasks"></i> Настройки системы</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

