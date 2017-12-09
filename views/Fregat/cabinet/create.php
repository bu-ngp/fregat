<?php

use app\func\Proc;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Cabinet */

$this->title = 'Добавить кабинет';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
    'model' => $model,
]);
?>

<div class="cabinet-create">
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading base-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
            ])
            ?>
        </div>
    </div>
</div>
