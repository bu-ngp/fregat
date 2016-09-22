<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use app\func\Proc;
use yii\helpers\Html;

$this->title = 'Ошибка';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="site-error">

    <h1 style="font-family: 'Comfortaa', cursive; color: #59010b;"><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= $name . ': ' . nl2br(Html::encode($message)) ?>
    </div>

    <p>
        Возникла ошибка на сервере, обратитесь к администратору
    </p>
    <div style="text-align: center;">
        <i style="font-size: 500px; color: #fed9be;" class="glyphicon glyphicon-exclamation-sign"></i>
    </div>
</div>
