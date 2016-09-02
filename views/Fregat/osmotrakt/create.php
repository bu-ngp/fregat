<?php
\Yii::$app->getView()->registerJsFile('js/osmotraktform.js');

use yii\helpers\Html;
use app\func\Proc;
use app\models\Fregat\TrOsnov;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Osmotrakt */

$this->title = 'Составить акт осмотра основной материальной ценности';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
    'model' => [$model],
]);
?>
<div class="osmotrakt-create">
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode($this->title) ?></div>
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
