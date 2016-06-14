<?php

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

<div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
    <div class="panel-heading">Настройки регистра глаукомных пациентов</div>
    <div class="panel-body">     
        <ul class="nav nav-pills nav-stacked">
            <?php if (Yii::$app->user->can('GlaukUserPermission')): ?>
                <li>
                    <?= Html::a('<i class="glyphicon glyphicon-list-alt"></i> Справочники', ['//Fregat/fregat/sprav'], ['class' => 'btn btn-default']); ?>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
