<?php
\Yii::$app->getView()->registerJsFile('@web/js/docfiles.js');

use yii\helpers\Html;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Recoveryrecieveakt */

$this->title = 'Результат восстановления материальной ценности';
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
                'searchModelrra' => $searchModelrra,
                'dataProviderrra' => $dataProviderrra,
            ])
            ?>
        </div>
    </div>
</div>
