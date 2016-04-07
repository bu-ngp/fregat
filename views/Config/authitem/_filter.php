<?php
//\Yii::$app->getView()->registerJsFile('/js/authitemfiltercontain.js');

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Import\Importconfig */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="authitemfilter-form">
    <?php $form = ActiveForm::begin(['options' => ['id' => 'authitemfilter-form', 'data-pjax' => true]]); ?>
    <div class="insideforms">
        <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
            <div class="panel-heading"><?= Html::encode('Основные') ?></div>
            <div class="panel-body">

                <?= $form->field($model, 'onlyrootauthitems')->checkbox(/* ['uncheck' => null] */); ?>


            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php //echo Html::submitButton('<i class="glyphicon glyphicon-ok"></i> Применить', ['class' => 'btn btn-primary']) ?>
                <?= Html::Button('<i class="glyphicon glyphicon-ok"></i> Применить', ['class' => 'btn btn-primary', 'id' => 'authitemfilter_apply']) ?>
                <?= Html::Button('<i class="glyphicon glyphicon-remove"></i> Отмена', ['class' => 'btn btn-danger', 'id' => 'authitemfilter_close']) ?>
            </div>
        </div> 
    </div>

    <?php ActiveForm::end(); ?>
</div>
