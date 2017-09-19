<?php

use app\models\Fregat\Grupa;
use app\models\Fregat\Material;
use app\models\Fregat\Recoveryrecieveakt;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="osmotraktfilter-form">
    <div class="form-group">
        <div class="row">
            <div class="col-xs-12">
                <?=
                yii\bootstrap\Html::input('text', null, null, ['class' => 'form-control inputuppercase searchfilterform', 'placeholder' => 'ПОИСК...', 'autofocus' => true])
                ?>
            </div>
        </div>
    </div>


    <?php $form = ActiveForm::begin(['options' => ['id' => $model->formName() . '-form', 'data-pjax' => true]]); ?>
    <div class="insideforms">

        <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?> panelblock">
            <div class="panel-heading"><?= Html::encode('Материальная ценность') ?></div>
            <div class="panel-body">

                <?php Proc::FilterFieldDateRange($form, $model, 'mattraffic_date_writeoff') ?>

            </div>
        </div>

        <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?> panelblock">
            <div class="panel-heading"><?= Html::encode('Журнал восстановления материальных ценностей') ?></div>
            <div class="panel-body">

                <?= $form->field($model, 'osmotrakt_recoverysendakt_exists_mark')->checkbox()->label(null, ['class' => 'control-label']); ?>

                <?= $form->field($model, 'osmotrakt_recoverysendakt_not_exists_mark')->checkbox()->label(null, ['class' => 'control-label']); ?>

                <?=
                $form->field($model, 'osmotrakt_recoveryrecieveakt_repaired')->widget(Select2::className(), [
                    'hideSearch' => true,
                    'data' => Recoveryrecieveakt::VariablesValues('recoveryrecieveakt_repaired'),
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'options' => ['placeholder' => 'Выберете статус восстановления', 'class' => 'form-control setsession'],
                    'theme' => Select2::THEME_BOOTSTRAP,
                ]);
                ?>

                <?= $form->field($model, 'osmotrakt_recoverysendakt_not_recieved_mark')->checkbox()->label(null, ['class' => 'control-label']); ?>

            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::button('<i class="glyphicon glyphicon-ok"></i> Применить', ['class' => 'btn btn-primary', 'id' => $model->formName() . '_apply']) ?>
                <?= Html::button('<i class="glyphicon glyphicon-remove"></i> Отмена', ['class' => 'btn btn-danger', 'id' => $model->formName() . '_close']) ?>
                <?= Html::button('<i class="glyphicon glyphicon-remove-sign"></i> Сброс', ['class' => 'btn btn-default', 'id' => $model->formName() . '_reset']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
