<?php
use app\func\Proc;
use yii\helpers\Html;

\Yii::$app->getView()->registerJsFile('@web/js/spisosnovaktform.js' . Proc::appendTimestampUrlParam(Yii::$app->basePath . '/web/js/spisosnovaktform.js'));

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Spisosnovakt */

$this->title = 'Обновить заявку на списание основных средств';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
    'model' => $model,
]);
?>
<div class="spisosnovakt-update">
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
