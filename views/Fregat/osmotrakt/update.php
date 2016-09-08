<?php
\Yii::$app->getView()->registerJsFile('js/osmotraktform.js');

use yii\helpers\Html;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Osmotrakt */

$this->title = 'Обновить акт осмотра основной материальной ценности';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
            'model' => [$model],
        ]);
?>
<div class="osmotrakt-update">
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
            ])
            ?>
        </div>
    </div>
</div>
