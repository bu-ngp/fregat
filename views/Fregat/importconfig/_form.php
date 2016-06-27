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
        <div class="panel-heading"><?= Html::encode('Основные средства') ?></div>
        <div class="panel-body">

            <?= $form->field($model, 'os_filename')->textInput(['maxlength' => true, 'autofocus' => true]) ?>

            <?= $form->field($model, 'os_startrow')->textInput() ?>

            <?= $form->field($model, 'os_material_1c')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'os_mattraffic_date')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'os_material_inv')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'os_material_name1c')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'os_material_price')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'os_employee_fio')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'os_dolzh_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'os_podraz_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'os_material_serial')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'os_material_release')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'os_material_status')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Материалы') ?></div>
        <div class="panel-body">
            <?= $form->field($model, 'mat_filename')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'mat_startrow')->textInput() ?>

            <?= $form->field($model, 'mat_material_1c')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'mat_material_inv')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'mat_material_name1c')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'mat_material_number')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'mat_izmer_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'mat_material_price')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'mat_employee_fio')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'mat_dolzh_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'mat_podraz_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'mat_material_tip_nomenklaturi')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Сотрудники') ?></div>
        <div class="panel-body">
            <?= $form->field($model, 'emp_filename')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Дополнительно') ?></div>
        <div class="panel-body">
            <?= $form->field($model, 'logreport_reportcount')->textInput() ?>

            <?=
            $form->field($model, 'max_execution_time', [
                'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon">' . Yii::$app->formatter->asDuration($model->max_execution_time) . '</span></div>'
            ])->textInput()
            ?>

            <?php Yii::$app->formatter->sizeFormatBase = 1000; ?>
            <?=
            $form->field($model, 'memory_limit', [
                'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon">' . Yii::$app->formatter->asShortSize($model->memory_limit) . '</span></div>'
            ])->textInput(['maxlength' => true])
            ?>
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
