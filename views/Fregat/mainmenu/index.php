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
<div class="menublock">
    <div class="menubutton mb_green" id="mb_prihod_j">
        <div class="menubutton_cn">Журнал материальных ценностей</div>
    </div>
    <div class="menubutton mb_yellow" id="mb_install_j">
        <div class="menubutton_cn">Журнал перемещений материальных ценностей</div>
    </div>
    <div class="menubutton mb_yellow" id="mb_remove_j">
        <div class="menubutton_cn">Журнал снятия комплектующих с материальных ценностей</div>
    </div>
    <div class="menubutton mb_red" id="mb_osmotr_j">
        <div class="menubutton_cn">Журнал осмотров материальных ценностей</div>
    </div>
    <div class="menubutton mb_blue" id="mb_recovery_j">
        <div class="menubutton_cn">Журнал восстановления материальных ценностей</div>
    </div>
</div>
<div class="menublock">
    <div class="menubutton mb_green" id="mb_prihod_new">   
        <div class="menubutton_cn">Составить акт прихода материальнной ценности</div>  
    </div>
    <div class="menubutton mb_yellow" id="mb_install_new">   
        <div class="menubutton_cn">Составить акт перемещения материальной ценности</div>  
    </div>
    <div class="menubutton mb_red" id="mb_osmotr_new">    
        <div class="menubutton_cn">Составить акт осмотра материальной ценности</div>
    </div>
    <div class="menubutton mb_blue" id="mb_recovery_new">    
        <div class="menubutton_cn">Составить акт восстановления материальных ценностей</div>  
    </div>
</div>
<div class="menublock">
    <div class="menubutton mb_gray" id="mb_importdata" data-height="200">   
        <div class="menubutton_cn">Импорт данных</div>  
    </div>
    <div class="menubutton mb_gray" id="mb_sprav" data-height="200">    
        <div class="menubutton_cn">Справочники</div>
    </div>
</div>