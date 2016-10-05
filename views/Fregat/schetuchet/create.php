<?php

use app\func\Proc;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Schetuchet */

$this->title = 'Создать Счет учета';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this,[
    'model' => $model,
]);
?>
<div class="schetuchet-create">
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
