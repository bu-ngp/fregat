<?php

use app\func\Proc;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Docfiles */

$this->title = 'Загрузить файл';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this,[
    'model' => $model,
]);
?>
<div class="docfiles-create">
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
