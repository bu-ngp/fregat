<?php
\Yii::$app->getView()->registerJsFile('js/freewall.js');
\Yii::$app->getView()->registerJsFile('js/fregatmainmenu.js');
use yii\helpers\Html;
use app\func\Proc;
use yii\helpers\Url;

$this->title = 'Настройки';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
            'addfirst' => [
                'label' => 'Регистр глаукомных пациентов',
                'url' => Url::toRoute('Base/patient/glaukindex'),
            ],
            'clearbefore' => true,
        ]);
?>

<div class="panel panel-<?= Yii::$app->params['panelStyle'] ?> menuplitka">
    <div class="panel-heading">Настройки регистра глаукомных пациентов</div>
    <div class="panel-body">
        <div class="menublock">
            <?php if (Yii::$app->user->can('GlaukUserPermission')): ?>
                <div class="menubutton menubutton_activeanim mb_blue" id="mb_glauksprav">
                    <span class="hoverspan"></span>
                    <div class="menubutton_cn"><i class="glyphicon glyphicon-list-alt"></i> Справочники</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

