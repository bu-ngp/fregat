<?php

use yii\helpers\Html;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Material */

$this->title = 'Карта материальной ценности';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
            'model' => [$model, $Mattraffic],
        ]);
?>
<div class="material-update">
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading base-heading base-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
                'Mattraffic' => $Mattraffic,
                'searchModel_mattraffic' => $searchModel_mattraffic,
                'dataProvider_mattraffic' => $dataProvider_mattraffic,
                'searchModel_recovery' => $searchModel_recovery,
                'dataProvider_recovery' => $dataProvider_recovery,
                'searchModel_recoverymat' => $searchModel_recoverymat,
                'dataProvider_recoverymat' => $dataProvider_recoverymat,
                'searchModel_recoverysend' => $searchModel_recoverysend,
                'dataProvider_recoverysend' => $dataProvider_recoverysend,
                'searchModel_recoverysendmat' => $searchModel_recoverysendmat,
                'dataProvider_recoverysendmat' => $dataProvider_recoverysendmat,
                'searchModel_mattraffic_contain' => $searchModel_mattraffic_contain,
                'dataProvider_mattraffic_contain' => $dataProvider_mattraffic_contain,
            ])
            ?>
        </div>
    </div>
</div>
