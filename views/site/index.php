<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
?>

<p>
<?= Html::a('Система "Фрегат"', ['Fregat/fregat/index'], ['class' => 'btn btn-primary']) ?>
</p>
<p>
<?= Html::a('Настройки портала', ['Config/config/index'], ['class' => 'btn btn-primary']) ?>
</p>