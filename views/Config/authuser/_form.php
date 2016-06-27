<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\web\Session;

/* @var $this yii\web\View */
/* @var $model app\models\Config\Authuser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="authuser-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'Authassignmentform',
    ]);
    ?>

    <?php
    echo $form->field($model, 'auth_user_fullname')->textInput(array_merge(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => $model->scenario === 'Changepassword'], $model->scenario === 'Changepassword' ? [] : ['autofocus' => true]));
    echo $form->field($model, 'auth_user_login')->textInput(['maxlength' => true, 'class' => 'form-control setsession', 'disabled' => $model->scenario === 'Changepassword']);


    if ($model->isNewRecord || $model->scenario === 'Changepassword') {
        echo $form->field($model, 'auth_user_password')->passwordInput(array_merge(['maxlength' => true, 'autocomplete' => 'off'], $model->scenario === 'Changepassword' ? ['autofocus' => true] : []));
        echo $form->field($model, 'auth_user_password2')->passwordInput(['maxlength' => true, 'autocomplete' => 'off']);
    }
    ?>

    <?php ActiveForm::end(); ?>

    <?php
    if (!$model->isNewRecord && $model->scenario !== 'Changepassword') {

        if (!$emp)
            echo DynaGrid::widget(Proc::DGopts([
                        'options' => ['id' => 'authassignmentgrid'],
                        'columns' => Proc::DGcols([
                            'columns' => [
                                'itemname.description',
                            ],
                            'buttons' => [
                                'deletecustom' => function ($url, $model) use ($params) {
                                    $customurl = Yii::$app->getUrlManager()->createUrl(['Config/authassignment/delete', 'item_name' => $model->item_name, 'user_id' => $model->user_id]);
                                    return Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                                                'type' => 'button',
                                                'title' => 'Удалить',
                                                'class' => 'btn btn-xs btn-danger',
                                                'onclick' => 'ConfirmDeleteDialogToAjax("Вы уверены, что хотите удалить запись?", "' . $customurl . '", "authassignmentgrid")'
                                    ]);
                                }],
                                ]),
                                'gridOptions' => [
                                    'dataProvider' => $dataProvider,
                                    'filterModel' => $searchModel,
                                    'panel' => [
                                        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-paperclip"></i> Привязать к роли</h3>',
                                        'before' => Html::a('<i class="glyphicon glyphicon-download"></i> Добавить роль', ['Config/authitem/forauthassignment',
                                            'foreignmodel' => 'Authassignment',
                                            'url' => $this->context->module->requestedRoute,
                                            'field' => 'item_name',
                                            'id' => $model->primaryKey,
                                                ], ['class' => 'btn btn-success', 'data-pjax' => '0']),
                                    ],
                                ]
                    ]));

                echo DynaGrid::widget(Proc::DGopts([
                            'options' => ['id' => 'employeeauthusergrid'],
                            'columns' => Proc::DGcols([
                                'columns' => [
                                    'employee_id',
                                    'iddolzh.dolzh_name',
                                    'idpodraz.podraz_name',
                                    'idbuild.build_name',
                                    [
                                        'attribute' => 'employee_dateinactive',
                                        'format' => 'date',
                                    ],
                                    [
                                        'attribute' => 'employee_importdo',
                                        'visible' => false,
                                        'value' => function ($model) {
                                            return $model->employee_importdo === 1 ? 'Да' : 'Нет';
                                        }
                                    ],
                                ],
                                'buttons' => [
                                    'update' => ['Fregat/employee/update', 'employee_id'],
                                    'deleteajax' => ['Fregat/employee/delete', 'employee_id', "employeeauthusergrid"],
                                ],
                            ]),
                            'gridOptions' => [
                                'dataProvider' => $dataProviderEmp,
                                'filterModel' => $searchModelEmp,
                                'panel' => [
                                    'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-paperclip"></i> Привязать к специальности</h3>',
                                    'before' => Html::a('<i class="glyphicon glyphicon-download"></i> Добавить специальность', ['Fregat/employee/create',
                                        'iduser' => $model->primaryKey,
                                            ], ['class' => 'btn btn-success', 'data-pjax' => '0']),
                                ],
                            ]
                ]));
            }
            ?>

            <div class="form-group">

                <?php
                $label = '<i class="glyphicon glyphicon-plus"></i> Создать';
                $class = 'btn btn-success';

                if (!$model->isNewRecord) {
                    if ($model->scenario === 'Changepassword') {
                        $label = '<i class="glyphicon glyphicon-lock"></i> Сменить пароль';
                        $class = 'btn btn-info';
                    } else {
                        $label = '<i class="glyphicon glyphicon-edit"></i> Обновить';
                        $class = 'btn btn-primary';
                    }
                }
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Назад', Proc::GetPreviousURLBreadcrumbsFromSession(), ['class' => 'btn btn-info']) ?>
                        <?= Html::submitButton($label, ['class' => $class, 'form' => 'Authassignmentform']) ?>
                        <?php if (!$model->isNewRecord && $model->scenario !== 'Changepassword' && Yii::$app->user->can('UserEdit') && !$emp): ?>
                            <?= Html::a('<i class="glyphicon glyphicon-lock"></i> Сменить пароль', ['Config/authuser/changepassword', 'id' => $model['auth_user_id']], ['class' => 'btn btn-info']) ?>
                        <?php endif; ?>
            </div>
        </div>


    </div>

</div>
