<?php
\Yii::$app->getView()->registerJsFile('/js/replacematerial.js');

use yii\helpers\Html;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\TrOsnov */

$this->title = 'Добавить перемещаемую материальную ценность';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
            'model' => [$model, $Mattraffic, $Material, $Employee],
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
                'Employee' => $Employee,
            ])
            ?>
        </div>
    </div>
</div>
