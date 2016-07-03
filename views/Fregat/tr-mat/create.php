<?php

use yii\helpers\Html;
use app\func\Proc;


/* @var $this yii\web\View */
/* @var $model app\models\Fregat\TrMat */

$this->title = 'Добавить комплектуемую материальную ценность';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
            'model' => [$model, $Mattraffic, $Material],
        ]);
?>
<div class="tr-osnov-create">
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
                'Mattraffic' => $Mattraffic,
                'Material' => $Material,
                'mattraffic_number_max' => $mattraffic_number_max,
            ])
            ?>
        </div>
    </div>
</div>
