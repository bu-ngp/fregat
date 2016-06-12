<?php

use yii\helpers\Html;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $model app\models\Base\Patient */

$this->title = 'Обновить словосочетание';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
            'model' => [$model, $Glprep],
        ]);
?>
<div class="patient-update">
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
                'Glaukuchet' => $Glaukuchet,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ])
            ?>
        </div>
    </div>
</div>
