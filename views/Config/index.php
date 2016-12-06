<?php
use app\func\Proc;
use yii\helpers\Url;

\Yii::$app->getView()->registerJsFile('@web/js/freewall.js' . Proc::appendTimestampUrlParam(Yii::$app->basePath . '/web/js/freewall.js'));
\Yii::$app->getView()->registerJsFile('@web/js/fregatmainmenu.js' . Proc::appendTimestampUrlParam(Yii::$app->basePath . '/web/js/fregatmainmenu.js'));

$this->title = 'Основные';

$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
    'addfirst' => [
        'label' => 'Настройки портала',
        'url' => Url::toRoute('Config/config/index'),
    ],
    'clearbefore' => true,
]);
?>

<div class="panel panel-<?= Yii::$app->params['panelStyle'] ?> menuplitka">
    <div class="panel-heading">Настройки портала</div>
    <div class="panel-body">
        <div class="menublock">
            <?php if (Yii::$app->user->can('UserEdit')): ?>
                <div class="menubutton menubutton_activeanim mb_yellow" id="mb_usermanager">
                    <span class="hoverspan"></span>
                    <div class="menubutton_cn">Менеджер пользователей</div>
                    <i class="glyphicon glyphicon-user"></i>
                </div>
            <?php endif; ?>
            <?php if (Yii::$app->user->can('RoleEdit')): ?>
                <div class="menubutton menubutton_activeanim mb_red" id="mb_rolemanager">
                    <span class="hoverspan"></span>
                    <div class="menubutton_cn">Менеджер ролей</div>
                    <i class="glyphicon glyphicon-align-justify"></i>
                </div>
            <?php endif; ?>
            <?php if (Yii::$app->user->can('FregatConfig')): ?>
                <div class="menubutton menubutton_activeanim mb_blue" id="mb_configuration">
                    <span class="hoverspan"></span>
                    <div class="menubutton_cn">Конфигурация</div>
                    <i class="glyphicon glyphicon-tasks"></i>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
