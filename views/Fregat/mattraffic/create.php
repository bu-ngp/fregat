<?php

use yii\helpers\Html;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Mattraffic */

$this->title = 'Сменить материально-ответственное лицо';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
    'model' => [$model, $Material],
]);
?>
<div class="mattraffic-create">
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading base-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
                'Material' => $Material,
                'searchModel_mattrafficmols' => $searchModel_mattrafficmols,
                'dataProvider_mattrafficmols' => $dataProvider_mattrafficmols,
            ])
            ?>
        </div>
    </div>
</div>
