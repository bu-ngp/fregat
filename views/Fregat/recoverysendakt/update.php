<?php

use yii\helpers\Html;
use app\func\Proc;

\Yii::$app->getView()->registerJsFile(Yii::$app->request->baseUrl . '/js/recoverysendaktform.js');

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Recoveryrecieveakt */

$this->title = 'Обновить акт восстановления материальных ценностей';
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
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'searchModelmat' => $searchModelmat,
                'dataProvidermat' => $dataProvidermat,
                'emailfrom' => $emailfrom,
                'emailtheme' => $emailtheme,
            ])
            ?>
        </div>
    </div>
</div>
