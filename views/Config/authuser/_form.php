<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\Session;

/* @var $this yii\web\View */
/* @var $model app\models\Config\Authuser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="authuser-form">

    <?php $form = ActiveForm::begin([
                'id' => 'Authassignmentform',
    ]); ?>

    <?= $form->field($model, 'auth_user_fullname')->textInput(['maxlength' => true, 'class' => 'form-control setsession']) ?>

    <?= $form->field($model, 'auth_user_login')->textInput(['maxlength' => true, 'class' => 'form-control setsession']) ?>

    <?php
    if ($model->isNewRecord) {
        echo $form->field($model, 'auth_user_password')->passwordInput(['maxlength' => true]);
        echo $form->field($model, 'auth_user_password2')->passwordInput(['maxlength' => true]);
    }
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>        
        <?php
        if (!$model->isNewRecord)
            echo Html::a('<i class="glyphicon glyphicon-lock"></i> Сменить пароль', ['changepassword'], ['class' => 'btn btn-info']);
        ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
    if (!$model->isNewRecord) {
        $session = new Session;
        $session->open();

        echo DynaGrid::widget(Proc::DGopts([
                    'columns' => Proc::DGcols([
                        'columns' => [
                            'idemployee.employee_id',
                            'idemployee.employee_fio',
                            'idemployee.iddolzh.dolzh_name',
                            'idemployee.idpodraz.podraz_name',
                            'idemployee.idbuild.build_name',
                        ],
                        'buttons' => [
                            'delete' => ['Fregat/impemployee/delete', 'impemployee_id',
                            ]
                        ],
                    ]),
                    'gridOptions' => [
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'options' => ['id' => 'impemployeegrid'],
                        'panel' => [
                            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-user"></i> Привязать к сотруднику</h3>',
                            'before' => Html::a('Добавить сотрудника', ['Fregat/employee/forimportemployee',
                                'foreignmodel' => 'Impemployee', //substr($model->className(), strrpos($model->className(), '\\') + 1),
                                'url' => $this->context->module->requestedRoute,
                                'field' => 'id_employee',
                                'id' => $model->primaryKey,
                                    // 'id' => $_GET['id'],
                                    ], ['class' => 'btn btn-success', 'data-pjax' => '0']),
                        ],
                    ]
        ]));

        $session->close();
    }
    ?>
    
</div>
