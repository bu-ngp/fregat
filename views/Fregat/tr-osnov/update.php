<?php

use yii\helpers\Html;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\TrOsnov */

$this->title = 'Обновить перемещаемую материальную ценность';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
    'model' => $model, $Mattraffic, $Material, $Employee,
]);
?>
<div class="tr-mat-osmotr-update">
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading base-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
                'Mattraffic' => $Mattraffic,
                'Material' => $Material, // Для просмотра
                'Employee' => $Employee, // Для просмотра
                'mattraffic_number_max' => $mattraffic_number_max,
            ])
            ?>
        </div>
    </div>
</div>