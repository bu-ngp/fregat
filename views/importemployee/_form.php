<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Button;
use app\models\Build;
use app\models\Podraz;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Importemployee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="importemployee-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'importemployee_combination')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'id_build')->textInput() ?>
    <?php // $form->field($model, 'id_podraz')->textInput() ?>

    <?=
    $form->field($model, 'id_podraz', [
        'template' => '{label}<div class="input-group">{input}<span class="input-group-btn"><button class="btn btn-success"><i class="glyphicon glyphicon-plus-sign"></i></button></span></div>{hint}{error}',
    ])->dropdownList(
            $id_podraz, ['prompt' => 'Выбрать подразделение', 'class' => 'form-control inactive', "disabled" => "disabled"]
    );
    ?>

    <?=
    $form->field($model, 'id_build', [
        'template' => '{label}<div class="input-group">{input}<span class="input-group-btn">' . Html::a('<i class="glyphicon glyphicon-plus-sign"></i>', ['build/index',
            'selectelement' => 'importemployee-id_build'
                ], ['class' => 'btn btn-success']) . '</span></div>{hint}{error}',
    ])->dropdownList(
            $id_build, ['prompt' => 'Выбрать здание', 'class' => 'form-control inactive', "disabled" => "disabled"]
    );
    ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
