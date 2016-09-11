<?php

use yii\helpers\Html;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Mattraffic */

$this->title = 'Сменить материально-ответственное лицо';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
    'model' => [$model, $Material, $Employee],
]);
?>
<div class="mattraffic-create">
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading base-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
                'Material' => $Material,
                'Employee' => $Employee,
            ])
            ?>
        </div>
    </div>
</div>
