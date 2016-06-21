<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use app\func\Proc;
use app\models\Base\Preparat;

/* @var $this yii\web\View */
/* @var $model app\models\Glauk\Glprep */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="glprep-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=
    $form->field($model, 'id_preparat')->widget(Select2::classname(), Proc::DGselect2([
                'model' => $model,
                'resultmodel' => new Preparat,
                'fields' => [
                    'keyfield' => 'id_preparat',
                    'resultfield' => 'preparat_name',
                ],
                'placeholder' => 'Выберете препарат',
                'fromgridroute' => 'Base/preparat/index',
                'resultrequest' => 'Base/preparat/selectinput',
                'thisroute' => $this->context->module->requestedRoute,
                'dopparams' => [
                    'idglaukuchet' => $idglaukuchet,
                ],
    ]));
    ?>

    <?=
    $form->field($model, 'glprep_rlocat')->widget(Select2::classname(), [
        'hideSearch' => true,
        'data' => $model::VariablesValues('glprep_rlocat'),
        'pluginOptions' => [
            'allowClear' => true
        ],
        'options' => ['placeholder' => 'Выберете категорию льготного лекарственного обеспечения', 'class' => 'form-control setsession'],
        'theme' => Select2::THEME_BOOTSTRAP,
    ]);
    ?>

    <div class="form-group">
        <div class="form-group">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div> 
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
