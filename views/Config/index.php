<?php
\Yii::$app->getView()->registerJsFile('js/freewall.js');
\Yii::$app->getView()->registerJsFile('js/fregatmainmenu.js');

use yii\helpers\Html;
use app\func\Proc;
use yii\helpers\Url;

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
                    <div class="menubutton_cn"><i class="glyphicon glyphicon-user"></i> Менеджер пользователей</div>
                </div>
            <?php endif; ?>
            <?php if (Yii::$app->user->can('RoleEdit')): ?>
                <div class="menubutton menubutton_activeanim mb_red" id="mb_rolemanager">
                    <span class="hoverspan"></span>
                    <div class="menubutton_cn"><i class="glyphicon glyphicon-align-justify"></i> Менеджер ролей</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
