<?php

use app\models\Fregat\Mattraffic;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use app\func\Proc;
use kartik\touchspin\TouchSpin;
use app\models\Config\Authuser;
use app\models\Fregat\Dolzh;
use app\models\Fregat\Podraz;
use app\models\Fregat\Build;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\TrOsnov */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tr-osnov-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Материальная ценность') ?></div>
        <div class="panel-body">

            <?=
            $form->field($model, 'id_mattraffic')->widget(Select2::classname(), array_merge(Proc::DGselect2([
                                'model' => $model,
                                'resultmodel' => new Mattraffic,
                                'fields' => [
                                    'keyfield' => 'id_mattraffic',
                                ],
                                'placeholder' => 'Введите инвентарный номер материальной ценности',
                                'fromgridroute' => 'Fregat/mattraffic/forinstallakt',
                                'resultrequest' => 'Fregat/tr-osnov/selectinputfortrosnov',
                                'thisroute' => $this->context->module->requestedRoute,
                                'methodquery' => 'selectinputfortrosnov',
                                'methodparams' => ['idinstallakt' => (string) filter_input(INPUT_GET, 'idinstallakt')],
                                'dopparams' => [
                                    'foreigndo' => '1',
                                    'idinstallakt' => (string) filter_input(INPUT_GET, 'idinstallakt'),
                                ],
                            ]), [
                'pluginEvents' => [
                    "select2:select" => "function() { FillTrOsnov(); }",
                    "select2:unselect" => "function() { ClearTrOsnov(); }"
                ],
            ]));
            ?>

            <?= $form->field($Material, 'material_tip', ['enableClientValidation' => false])->dropDownList([0 => '', 1 => 'Основное средство', 2 => 'Материал', 3 => 'Групповой учет'], ['class' => 'form-control setsession', 'disabled' => true]) ?>

            <?= ''//$form->field(Proc::RelatModelValue($model,'idMattraffic.idMaterial', new \app\models\Fregat\Material), 'material_name', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => true]) ?>

            <?= $form->field($Material, 'material_name', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => true]) ?>

            <?= $form->field($Material, 'material_writeoff', ['enableClientValidation' => false])->dropDownList([0 => 'Нет', 1 => 'Да'], ['class' => 'form-control setsession', 'disabled' => true]) ?>

        </div>
    </div>
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Материально-ответственное лицо') ?></div>
        <div class="panel-body">

            <?= $form->field(isset($Employee->idperson) ? $Employee->idperson : new Authuser, 'auth_user_fullname', ['enableClientValidation' => false])->textInput(['class' => 'form-control setsession', 'disabled' => true]) ?>

            <?= $form->field(isset($Employee->iddolzh) ? $Employee->iddolzh : new Dolzh, 'dolzh_name', ['enableClientValidation' => false])->textInput(['class' => 'form-control setsession', 'disabled' => true]) ?>

            <?= $form->field(isset($Employee->idpodraz) ? $Employee->idpodraz : new Podraz, 'podraz_name', ['enableClientValidation' => false])->textInput(['class' => 'form-control setsession', 'disabled' => true]) ?>

            <?= $form->field(isset($Employee->idbuild) ? $Employee->idbuild : new Build, 'build_name', ['enableClientValidation' => false])->textInput(['class' => 'form-control setsession', 'disabled' => true]) ?>

        </div>
    </div>
    <?=
    $form->field($Mattraffic, 'mattraffic_number', [
        'inputTemplate' => '<div class="input-group">{input}<span id="mattraffic_number_max" class="input-group-addon">' . $mattraffic_number_max . '</span></div>'
    ])->widget(TouchSpin::classname(), [
        'options' => ['class' => 'form-control setsession'],
        'pluginOptions' => [
            'verticalbuttons' => true,
            'min' => 0.001,
            'max' => 10000000000,
            'step' => 1,
            'decimals' => 3,
            'forcestepdivisibility' => 'none',
        ]
    ]);
    ?>

    <?= $form->field($model, 'tr_osnov_kab')->textInput(['maxlength' => true, 'class' => 'form-control setsession']) ?>    

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                
                <?= Html::submitButton('<i class="glyphicon glyphicon-plus"></i> Добавить', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
