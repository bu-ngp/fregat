<?php

use yii\helpers\Html;
use app\func\Proc;

\Yii::$app->getView()->registerJsFile('@web/js/materialform_update.js' . Proc::appendTimestampUrlParam(Yii::$app->basePath . '/web/js/materialform_update.js'));
\Yii::$app->getView()->registerJsFile('@web/js/docfiles.js' . Proc::appendTimestampUrlParam(Yii::$app->basePath . '/web/js/docfiles.js'));

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Material */

$this->title = 'Карта материальной ценности' . ' (' . explode(' ', $model->material_name)[0] . '...)';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
    'model' => [$model/*, $Mattraffic*/],
    'postfix' => $model->primaryKey,
]);
?>
<div class="material-update">
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading base-heading base-heading"><?= Html::encode('Карта материальной ценности') ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
                'Mattraffic' => $Mattraffic,
                'UploadFile' => $UploadFile,
                'searchModel_mattraffic' => $searchModel_mattraffic,
                'dataProvider_mattraffic' => $dataProvider_mattraffic,
                'searchModelmd' => $searchModelmd,
                'dataProvidermd' => $dataProvidermd,
                'searchModel_recovery' => $searchModel_recovery,
                'dataProvider_recovery' => $dataProvider_recovery,
                'searchModel_recoverymat' => $searchModel_recoverymat,
                'dataProvider_recoverymat' => $dataProvider_recoverymat,
                'searchModel_recoverysend' => $searchModel_recoverysend,
                'dataProvider_recoverysend' => $dataProvider_recoverysend,
                'searchModel_recoverysendmat' => $searchModel_recoverysendmat,
                'dataProvider_recoverysendmat' => $dataProvider_recoverysendmat,
                'searchModel_mattraffic_contain' => $searchModel_mattraffic_contain,
                'dataProvider_mattraffic_contain' => $dataProvider_mattraffic_contain,
                'searchModel_spisosnovakt' => $searchModel_spisosnovakt,
                'dataProvider_spisosnovakt' => $dataProvider_spisosnovakt,
                'searchModel_spismat' => $searchModel_spismat,
                'dataProvider_spismat' => $dataProvider_spismat,
                'searchModel_naklad' => $searchModel_naklad,
                'dataProvider_naklad' => $dataProvider_naklad,
                'gallery' => $gallery,
            ])
            ?>
        </div>
    </div>
</div>
