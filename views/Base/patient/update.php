<?php
if ($patienttype === 'glauk')
    \Yii::$app->getView()->registerJsFile(Yii::$app->request->baseUrl .'/js/glaukpatient.js');

use yii\helpers\Html;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $model app\models\Base\Patient */

$this->title = 'Карта пациента';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
            'model' => array_merge([$model, $Fias], $patienttype === 'glauk' ? [$dopparams['Glaukuchet']] : []),
        ]);
?>
<div class="patient-update">
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', array_merge([
                'model' => $model,
                'Fias' => $Fias,
                'patienttype' => $patienttype,
                            ], ['dopparams' => $dopparams]))
            ?>
        </div>
    </div>
</div>
