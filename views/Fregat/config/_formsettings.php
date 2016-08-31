<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Import\Importconfig */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="importconfig-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Отправка актов передачи материальных ценностей сторонней организации по электронной почте') ?></div>
        <div class="panel-body">

            <?= $form->field($model, 'fregatsettings_recoverysend_emailtheme')->textInput(['maxlength' => true, 'autofocus' => true]) ?>

            <?= $form->field($model, 'fregatsettings_recoverysend_emailfrom')->textInput() ?>

        </div>
    </div>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Назад', Proc::GetPreviousURLBreadcrumbsFromSession(), ['class' => 'btn btn-info']) ?>
                <?= Html::submitButton('<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => 'btn btn-primary']) ?>
            </div>
        </div> 
    </div>

    <?php ActiveForm::end(); ?>

</div>
