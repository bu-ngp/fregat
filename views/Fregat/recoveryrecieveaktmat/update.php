<?php
\Yii::$app->getView()->registerJsFile('@web/js/docfiles.js');

use yii\helpers\Html;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Recoveryrecieveaktmat */

$this->title = 'Результат восстановления материала';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
            'model' => $model,
        ]);
?>
<div class="recoveryrecieveakt-update">
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading base-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
                'UploadFile' => $UploadFile,
                'dataProvider' => $dataProvider,
                'searchModelrramat' => $searchModelrramat,
                'dataProviderrramat' => $dataProviderrramat,
            ])
            ?>
        </div>
    </div>
</div>
