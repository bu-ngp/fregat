<?php
\Yii::$app->getView()->registerJsFile('js/freewall.js');
\Yii::$app->getView()->registerJsFile('js/fregatmainmenu.js');

use yii\helpers\Html;
use app\func\Proc;
use yii\helpers\Url;

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
    Yii::$app->user->can('RemoveaktEdit') ||
    Yii::$app->user->can('OsmotraktEdit') ||
    Yii::$app->user->can('RecoveryEdit')
): ?>
    <div class="menublock">
        <?php if (Yii::$app->user->can('FregatUserPermission')): ?>
            <div class="menubutton mb_green" id="mb_prihod_j">
                <div class="menubutton_cn">Журнал материальных ценностей</div>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('InstallEdit')): ?>
            <div class="menubutton mb_yellow" id="mb_install_j">
                <div class="menubutton_cn">Журнал перемещений материальных ценностей</div>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('RemoveaktEdit')): ?>
            <div class="menubutton mb_yellow" id="mb_remove_j">
                <div class="menubutton_cn">Журнал снятия комплектующих с материальных ценностей</div>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('OsmotraktEdit')): ?>
            <div class="menubutton mb_red" id="mb_osmotr_j">
                <div class="menubutton_cn">Журнал осмотров материальных ценностей</div>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('OsmotraktEdit')): ?>
            <div class="menubutton mb_red" id="mb_osmotrmat_j">
                <div class="menubutton_cn">Журнал осмотров материалов</div>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('RecoveryEdit')): ?>
            <div class="menubutton mb_blue" id="mb_recovery_j">
                <div class="menubutton_cn">Журнал восстановления материальных ценностей</div>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php if (Yii::$app->user->can('FregatUserPermission') ||
    Yii::$app->user->can('FregatImport')
): ?>
    <div class="menublock">
        <?php if (Yii::$app->user->can('FregatImport')): ?>
            <div class="menubutton mb_gray" id="mb_importdata" data-height="200">
                <div class="menubutton_cn">Импорт данных</div>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('FregatUserPermission')): ?>
            <div class="menubutton mb_gray" id="mb_sprav" data-height="200">
                <div class="menubutton_cn">Справочники</div>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
<div class="menublock">
    <style>
      /*  div.wrap {
            background-color: #434343;
            background-image:linear-gradient(rgba(225, 220, 56, 1), rgba(221, 87, 85, 1));
        }

        div.container {
            color: white;
            background: repeating-linear-gradient(
                45deg,
                rgb(255, 217, 100),
                rgb(255, 217, 100) 10px,
                rgb(255, 186, 65) 10px,
                rgb(255, 186, 65) 20px
            );
        }

        .mb_test {
            background: radial-gradient(at top, rgba(255, 255, 255, 0.65),rgba(255, 255, 255, 0.14));
            border: solid 10px rgba(255, 255, 255, 0.35);;
        }*/
    </style>
  <!--  <div class="menubutton mb_test" id="mb_test" data-height="400">
        <div class="menubutton_cn">Тестовая плитка</div>
    </div>-->
</div>
