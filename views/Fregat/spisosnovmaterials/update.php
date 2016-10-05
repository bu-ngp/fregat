<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Spisosnovmaterials */

$this->title = 'Обновить материальную ценность';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
    'model' => [$model, $Mattraffic],
]);
?>
<div class="spisosnovmaterials-update">
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading base-heading base-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
            ])
            ?>
        </div>
    </div>
</div>
