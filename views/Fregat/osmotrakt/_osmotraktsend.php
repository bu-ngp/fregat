<?php

use app\models\Fregat\Organ;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="osmotraktsendfilter-form">
    <?php $form = ActiveForm::begin(['options' => ['id' => $model->formName() . '-form', 'data-pjax' => true]]); ?>
    <div class="insideforms">

        <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?> panelblock">
            <div class="panel-heading"><?= Html::encode('Организация в которую отправляем акт') ?></div>
            <div class="panel-body">
                <div class="errordialog" style="display: none;">

                </div>

                <?=
                Html::hiddenInput('osmotrakt_id',$osmotrakt_id,['id' => 'osmotrakt-osmotrakt_id']);
                ?>

                <?=
                $form->field($model, 'organ_id')->widget(Select2::classname(), Proc::DGselect2([
                    'model' => $model,
                    'resultmodel' => new Organ,
                    'fields' => [
                        'keyfield' => 'organ_id',
                        'resultfield' => 'organ_name',
                    ],
                    'placeholder' => 'Выберете организацию',
                    'resultrequest' => 'Fregat/organ/selectinput',
                    'thisroute' => $this->context->module->requestedRoute,
                    'onlyAjax' => false,
                ]));
                ?>

            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::Button('<i class="glyphicon glyphicon-ok"></i> Отправить', ['class' => 'btn btn-primary ', 'id' => 'SendOsmotraktDialog_apply']) ?>
                <?= Html::Button('<i class="glyphicon glyphicon-remove"></i> Отмена', ['class' => 'btn btn-danger', 'id' => 'SendOsmotraktDialog_close']) ?>
              </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
