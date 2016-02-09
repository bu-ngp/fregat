<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Dolzh;
use app\models\Podraz;
use app\models\Build;
use kartik\select2\Select2;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'employee_fio')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'id_dolzh')->widget(Select2::classname(), Proc::DGselect2([
                'model' => $model,
                'resultmodel' => new Dolzh,
                'fields' => [
                    'keyfield' => 'id_dolzh',
                    'resultfield' => 'dolzh_name',
                ],
                'placeholder' => 'Выберете должность',
                'fromgridroute' => 'dolzh/index',
                'resultrequest' => 'dolzh/selectinput',
                'thisroute' => $this->context->module->requestedRoute,
    ]));
    ?>

    <?=
    $form->field($model, 'id_podraz')->widget(Select2::classname(), Proc::DGselect2([
                'model' => $model,
                'resultmodel' => new Podraz,
                'fields' => [
                    'keyfield' => 'id_podraz',
                    'resultfield' => 'podraz_name',
                ],
                'placeholder' => 'Выберете подразделение',
                'fromgridroute' => 'podraz/index',
                'resultrequest' => 'podraz/selectinput',
                'thisroute' => $this->context->module->requestedRoute,
    ]));
    ?>

    <?=
    $form->field($model, 'id_build')->widget(Select2::classname(), Proc::DGselect2([
                'model' => $model,
                'resultmodel' => new Build,
                'fields' => [
                    'keyfield' => 'id_build',
                    'resultfield' => 'build_name',
                ],
                'placeholder' => 'Выберете здание',
                'fromgridroute' => 'build/index',
                'resultrequest' => 'build/selectinput',
                'thisroute' => $this->context->module->requestedRoute,
    ]));
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
