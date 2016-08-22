<?php

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

<div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
    <div class="panel-heading">Настройки системы "Фрегат"</div>
    <div class="panel-body">     
        <ul class="nav nav-pills nav-stacked">
            <?php if (Yii::$app->user->can('FregatImport')): ?>
                <li>
                    <?= Html::a('<i class="glyphicon glyphicon-import"></i> Импорт данных', ['import'], ['class' => 'btn btn-default']) ?>
                </li>
            <?php endif; ?>
            <?php if (Yii::$app->user->can('FregatUserPermission')): ?>
                <li>
                    <?= Html::a('<i class="glyphicon glyphicon-list-alt"></i> Справочники', ['sprav'], ['class' => 'btn btn-default']) ?>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<div class="form-group">
    <div class="panel panel-default">
        <div class="panel-heading">
            <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Назад', Proc::GetPreviousURLBreadcrumbsFromSession(), ['class' => 'btn btn-info']) ?>
        </div>
    </div>
</div>
