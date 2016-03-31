<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
?>

<div class="panel panel-info">
    <div class="panel-heading">Главное меню</div>
    <div class="panel-body">     
        <ul class="nav nav-pills nav-stacked">
            <li><?php echo Html::a('<i class="glyphicon glyphicon-list-alt"></i> Система "Фрегат"', ['Fregat/mattraffic/index'], ['class' => 'btn btn-default']); ?></li>
            <?php if (Yii::$app->user->can('UserEdit') || Yii::$app->user->can('RoleEdit')): ?>
                <li>
                    <?= Html::a('<i class="glyphicon glyphicon-wrench"></i> Настройки портала', ['Config/config/index'], ['class' => 'btn btn-default']); ?>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>

