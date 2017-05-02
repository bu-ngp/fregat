<?php
use app\func\Proc;
use yii\helpers\Html;

\Yii::$app->getView()->registerJsFile('@web/js/spismatform.js' . Proc::appendTimestampUrlParam(Yii::$app->basePath . '/web/js/spismatform.js'));

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Spismat */

$this->title = 'Обновить ведомость на списание материалов';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
    'model' => $model,
]);
?>
<div class="spismat-update">
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading base-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ])
            ?>
        </div>
    </div>
</div>
