<?php
\Yii::$app->getView()->registerJsFile('js/tr-matform.js');
use yii\helpers\Html;
use app\func\Proc;


/* @var $this yii\web\View */
/* @var $model app\models\Fregat\TrMat */

$this->title = 'Добавить комплектуемую материальную ценность';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
            'model' => [$model, $Mattraffic],
        ]);
?>
<div class="tr-osnov-create">
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading base-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
                'Mattraffic' => $Mattraffic,
                'mattraffic_number_max' => $mattraffic_number_max,
            ])
            ?>
        </div>
    </div>
</div>
