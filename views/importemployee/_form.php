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
    $form->field($model, 'id_podraz',[
        //'template' => "{label}\n<div class='input-append'>{input}<button class='btn btn-lg btn-success glyphicon glyphicon-plus-sign'></button></div>\n{hint}\n{error}",
    'template' => '<div class="input-group">{label}{input}<span class="input-group-btn"><button class="btn btn-lg btn-success glyphicon glyphicon-plus-sign"></button></span></div>{hint}{error}',
    
        
    ])->dropdownList(
            Podraz::find()->select(['podraz_name', 'podraz_id'])->indexBy('podraz_id')->column(), ['prompt' => 'Выбрать подразделение', 'class' => 'form-control inactive', "disabled" => "disabled"]
    );
    

  /*  echo Button::widget([
        'label' => '',
        'options' => ['class' => 'btn btn-lg btn-success glyphicon glyphicon-plus-sign'],
    ]);*/
    
    ?>
    
    <?=
    $form->field($model, 'id_build')->dropdownList(
            Build::find()->select(['build_name', 'build_id'])->indexBy('build_id')->column(), ['prompt' => 'Выбрать здание', 'class' => 'form-control inactive', "disabled" => "disabled"]
    );
    ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
