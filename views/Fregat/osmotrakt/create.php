<?php
use yii\helpers\Html;
use app\func\Proc;

\Yii::$app->getView()->registerJsFile('@web/js/osmotraktform.js' . Proc::appendTimestampUrlParam(Yii::$app->basePath . '/web/js/osmotraktform.js'));

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Osmotrakt */

$this->title = 'Составить акт осмотра основной материальной ценности';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
    'model' => [$InstallTrOsnov, $model],
]);
?>
<div class="osmotrakt-create">
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading base-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
                'InstallTrOsnov' => $InstallTrOsnov,
            ])
            ?>
        </div>
    </div>
</div>
