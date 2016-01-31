<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
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
    $form->field($model, 'id_podraz')->dropdownList(
            Podraz::find()->select(['podraz_name', 'podraz_id'])->indexBy('podraz_id')->column(), ['prompt' => 'Выбрать подразделение']
    );
    ?>

    <?=
    $form->field($model, 'id_build')->dropdownList(
            Build::find()->select(['build_name', 'build_id'])->indexBy('build_id')->column(), ['prompt' => 'Выбрать здание']
    );
    ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
