<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\BuildSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $emp ? 'Сотрудники' : 'Пользователи';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="authuser-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    echo DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'authusergrid'],
                'columns' => Proc::DGcols([
                    'columns' => array_merge([
                        'auth_user_id',
                        'auth_user_fullname',
                            ], $emp ? [] : ['auth_user_login']),
                    'buttons' => array_merge(Yii::$app->user->can('UserEdit') && !$emp ? [
                                'changepassword' => function ($url, $model, $key) {
                                    $customurl = Url::to(['Config/authuser/changepassword', 'id' => $model['auth_user_id']]);
                                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-lock"></i>', $customurl, ['title' => 'Изменить пароль', 'class' => 'btn btn-xs btn-info', 'data-pjax' => '0']);
                                },
                                            ] : [], Yii::$app->user->can('UserEdit') || Yii::$app->user->can('EmployeeEdit') ? [
                                        'update' => function ($url, $model) use ($emp) {
                                            $customurl = Yii::$app->getUrlManager()->createUrl(['Config/authuser/update', 'id' => $model['auth_user_id'], 'emp' => $emp]);
                                            return \yii\helpers\Html::a('<i class="glyphicon glyphicon-pencil"></i>', $customurl, ['title' => 'Обновить', 'class' => 'btn btn-xs btn-warning', 'data-pjax' => '0']);
                                        },
                                                    ] : [], Yii::$app->user->can('UserEdit') ? [
                                                'deleteajax' => ['Config/authuser/delete', 'auth_user_id'],
                                                    ] : []
                                    ),
                                ]),
                                'gridOptions' => [
                                    'dataProvider' => $dataProvider,
                                    'filterModel' => $searchModel,
                                    'panel' => [
                                        'heading' => '<i class="glyphicon glyphicon-user"></i> ' . $this->title,
                                        'before' => Yii::$app->user->can('UserEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
                                    ],
                                ]
                    ]));
                    ?>

                </div>
                <div class="form-group">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Назад', Proc::GetPreviousURLBreadcrumbsFromSession(), ['class' => 'btn btn-info']) ?>
        </div>
    </div>
</div>