<?php

use yii\helpers\Html;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Installakt */

$this->title = 'Обновить акт установки материальной ценности';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
            'model' => $model,
        ]);
?>
<div class="installakt-update">
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading base-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
                'searchModelOsn' => $searchModelOsn,
                'dataProviderOsn' => $dataProviderOsn,
                'searchModelMat' => $searchModelMat,
                'dataProviderMat' => $dataProviderMat,
            ])
            ?>
        </div>
    </div>
</div>
