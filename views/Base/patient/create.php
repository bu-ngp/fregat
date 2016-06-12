<?php
\Yii::$app->getView()->registerJsFile('js/glaukpatient.js');

use yii\helpers\Html;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $model app\models\Base\Patient */

$this->title = 'Создать нового пациента';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
            'model' => $model,
        ]);
?>
<div class="patient-create">
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
                'Glaukuchet' => $Glaukuchet,
                'Fias' => $Fias,
            ])
            ?>
        </div>
    </div>    
</div>
