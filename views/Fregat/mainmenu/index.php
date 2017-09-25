<?php
use app\func\Proc;
use yii\helpers\Url;

\Yii::$app->getView()->registerJsFile('@web/js/freewall.js' . Proc::appendTimestampUrlParam(Yii::$app->basePath . '/web/js/freewall.js'));
\Yii::$app->getView()->registerJsFile('@web/js/fregatmainmenu.js' . Proc::appendTimestampUrlParam(Yii::$app->basePath . '/web/js/fregatmainmenu.js'));

$this->title = 'Основное меню';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
    'addfirst' => [
        'label' => 'Фрегат',
        'url' => Url::toRoute('Fregat/fregat/mainmenu'),
    ],
    'clearbefore' => true,
]);
?>
<?php if (Yii::$app->user->can('FregatUserPermission') ||
    Yii::$app->user->can('InstallEdit') ||
    Yii::$app->user->can('RemoveaktEdit')
): ?>
    <div class="menublock">
        <?php if (Yii::$app->user->can('FregatUserPermission')): ?>
            <div class="menubutton menubutton_activeanim mb_green" id="mb_prihod_j">
                <span class="hoverspan"></span>
                <div class="menubutton_cn">Журнал материальных ценностей</div>
                <i class="glyphicon glyphicon-picture"></i>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('InstallEdit')): ?>
            <div class="menubutton menubutton_activeanim mb_yellow" id="mb_install_j">
                <span class="hoverspan"></span>
                <div class="menubutton_cn">Журнал установки материальных ценностей</div>
                <i class="glyphicon glyphicon-random"></i>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('RemoveaktEdit')): ?>
            <div class="menubutton menubutton_activeanim mb_yellow" id="mb_remove_j" data-height="200">
                <span class="hoverspan"></span>
                <div class="menubutton_cn">Журнал снятия комплектующих с материальных ценностей</div>
                <i class="glyphicon glyphicon-paste"></i>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('NakladEdit')): ?>
            <div class="menubutton menubutton_activeanim mb_yellow" id="mb_naklad_j">
                <span class="hoverspan"></span>
                <div class="menubutton_cn">Журнал требований - накладных</div>
                <i class="glyphicon glyphicon-bell"></i>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php if (Yii::$app->user->can('FregatUserPermission') ||
    Yii::$app->user->can('OsmotraktEdit') ||
    Yii::$app->user->can('RecoveryEdit')
): ?>
    <div class="menublock">
        <?php if (Yii::$app->user->can('OsmotraktEdit')): ?>
            <div class="menubutton menubutton_activeanim mb_red" id="mb_osmotr_j">
                <span class="hoverspan"></span>
                <div class="menubutton_cn">Журнал осмотров материальных ценностей</div>
                <i class="glyphicon glyphicon-search"></i>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('OsmotraktEdit')): ?>
            <div class="menubutton menubutton_activeanim mb_red" id="mb_osmotrmat_j">
                <span class="hoverspan"></span>
                <div class="menubutton_cn">Журнал осмотров материалов</div>
                <i class="glyphicon glyphicon-search"></i>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('RecoveryEdit')): ?>
            <div class="menubutton menubutton_activeanim mb_blue" id="mb_recovery_j">
                <span class="hoverspan"></span>
                <div class="menubutton_cn">Журнал восстановления материальных ценностей</div>
                <i class="glyphicon glyphicon-wrench"></i>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('SpisosnovaktEdit')): ?>
            <div class="menubutton menubutton_activeanim mb_green" id="mb_spisosnov_j">
                <span class="hoverspan"></span>
                <div class="menubutton_cn">Журнал списания основных средств</div>
                <i class="glyphicon glyphicon-trash"></i>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('SpismatEdit')): ?>
            <div class="menubutton menubutton_activeanim mb_green" id="mb_spismat_j">
                <span class="hoverspan"></span>
                <div class="menubutton_cn">Журнал списания материалов</div>
                <i class="glyphicon glyphicon-th"></i>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php if (Yii::$app->user->can('FregatUserPermission') ||
    Yii::$app->user->can('FregatImport') ||
    Yii::$app->user->can('FregatConfig') ||
    Yii::$app->user->can('UserEdit') ||
    Yii::$app->user->can('RoleEdit')
): ?>
    <div class="menublock">
        <?php if (Yii::$app->user->can('FregatImport')): ?>
            <div class="menubutton menubutton_activeanim mb_gray" id="mb_importdata">
                <span class="hoverspan"></span>
                <div class="menubutton_cn">Импорт данных</div>
                <i class="glyphicon glyphicon-import"></i>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('FregatUserPermission')): ?>
            <div class="menubutton menubutton_activeanim mb_gray" id="mb_sprav">
                <span class="hoverspan"></span>
                <div class="menubutton_cn">Справочники</div>
                <i class="glyphicon glyphicon-list-alt"></i>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('FregatConfig') || Yii::$app->user->can('UserEdit') || Yii::$app->user->can('RoleEdit')): ?>
            <div class="menubutton menubutton_activeanim mb_gray" id="mb_fregatoptions">
                <span class="hoverspan"></span>
                <div class="menubutton_cn">Настройки</div>
                <i class="glyphicon glyphicon-tasks"></i>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
