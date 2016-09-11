<?php

use yii\helpers\Html;
use app\func\Proc;
use app\models\Config\Authassignment;

$this->title = 'Обновить пользователя';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
            'model' => [$model, new Authassignment],
        ]);
?>
<div class="authuser-update">
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading base-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
                'emp' => $emp,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'searchModelEmp' => $searchModelEmp,
                'dataProviderEmp' => $dataProviderEmp,
                'EmployeeSpecEdit' => $EmployeeSpecEdit,
            ])
            ?>
        </div>
    </div>
</div>
