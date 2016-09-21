<?php
\Yii::$app->getView()->registerJsFile('js/freewall.js');
\Yii::$app->getView()->registerJsFile('js/fregatmainmenu.js');
?>

<div class="panel panel-<?= Yii::$app->params['panelStyle'] ?> menuplitka">
    <div class="panel-heading">Главное меню</div>
    <div class="panel-body">
        <div class="menublock">
            <?php if (Yii::$app->user->can('FregatUserPermission')): ?>
                <div class="menubutton menubutton_activeanim mb_red" id="mb_fregat">
                    <span class="hoverspan"></span>
                    <div class="menubutton_cn"><i class="glyphicon glyphicon-list-alt"></i> Система "Фрегат"</div>
                </div>
            <?php endif; ?>
            <?php if (Yii::$app->user->can('GlaukUserPermission')): ?>
                <div class="menubutton menubutton_activeanim mb_yellow" id="mb_glauk">
                    <span class="hoverspan"></span>
                    <div class="menubutton_cn"><i class="glyphicon glyphicon-search"></i> Регистр глаукомных пациентов</div>
                </div>
            <?php endif; ?>
            <?php if (Yii::$app->user->can('UserEdit') || Yii::$app->user->can('RoleEdit')): ?>
                <div class="menubutton menubutton_activeanim mb_gray" id="mb_config">
                    <span class="hoverspan"></span>
                    <div class="menubutton_cn"><i class="glyphicon glyphicon-wrench"></i> Настройки портала</div>
                </div>
            <?php endif; ?>
            <div class="menubutton menubutton_activeanim mb_blue" id="mb_changepassword">
                <span class="hoverspan"></span>
                <div class="menubutton_cn"><i class="glyphicon glyphicon-lock"></i> Сменить пароль</div>
            </div>
        </div>
    </div>
</div>

