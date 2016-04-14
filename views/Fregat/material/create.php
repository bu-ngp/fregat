<?php

use yii\helpers\Html;
use app\func\Proc;


/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Material */

$this->title = 'Составить акт прихода материальной ценности';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
            'model' => $model,
        ]);
?>
<div class="material-create">
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
                'Mattraffic' => $Mattraffic,
            ])
            ?>
        </div>
    </div> 
</div>
