<?php

use app\func\Proc;
use yii\helpers\Html;

\Yii::$app->getView()->registerJsFile('@web/js/nakladmaterialsform.js');

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Nakladmaterials */

$this->title = 'Добавить материальную ценность в требование-накладную';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
    'model' => $model,
]);
?>
<div class="nakladmaterials-create">
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading base-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
                'mattraffic_number_max' => $mattraffic_number_max,
            ])
            ?>
        </div>
    </div>
</div>
