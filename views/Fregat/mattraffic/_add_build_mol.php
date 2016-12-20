<?php

use app\models\Fregat\Grupa;
use app\models\Fregat\Material;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
$model = new \app\models\Fregat\Build();
?>

<div class="addbuildmol-form">
    <?php $form = ActiveForm::begin(['options' => ['id' => $model->formName() . '-form', 'data-pjax' => true]]); ?>
    <div class="insideforms">

        <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?> panelblock">
            <div class="panel-heading"><?= Html::encode('Здание') ?></div>
            <div class="panel-body">

                <?=
                $form->field($model, 'build_id')->widget(Select2::classname(), Proc::DGselect2([
                    'model' => $model,
                    'resultmodel' => new \app\models\Fregat\Build,
                    'placeholder' => 'Введите здание',
                    'setsession' => false,
                    'fields' => [
                        'keyfield' => 'build_id',
                        'resultfield' => 'build_name',
                    ],
                    'resultrequest' => 'Fregat/build/selectinput',
                    'thisroute' => $this->context->module->requestedRoute,
                    'onlyAjax' => false,
                ]))->label(false);
                ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::button('<i class="glyphicon glyphicon-ok"></i> Применить', ['class' => 'btn btn-primary', 'id' => $model->formName() . '_apply']) ?>
                <?= Html::button('<i class="glyphicon glyphicon-remove"></i> Отмена', ['class' => 'btn btn-danger', 'id' => $model->formName() . '_close']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
