<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\BuildSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="authuser-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="btn-group">
        <?= Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success']); ?>
    </div>

    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    echo DynaGrid::widget(Proc::DGopts([
                'columns' => Proc::DGcols([
                    'columns' => [
                        'auth_user_fullname',
                        'auth_user_login',
                    ],
                    'buttons' => array_merge([
                        'changepassword' => function ($url, $model, $key) {
                            $customurl = Url::to(['Config/authuser/changepassword', 'id' => $model['auth_user_id']]);
                            return \yii\helpers\Html::a('<i class="glyphicon glyphicon-lock"></i>', $customurl, ['title' => 'Изменить пароль', 'class' => 'btn btn-xs btn-info', 'data-pjax' => '0']);
                        },
                                'update' => ['Config/authuser/update', 'auth_user_id'],
                                'delete' => ['Config/authuser/delete', 'auth_user_id'],
                                    ]
                            ),
                        ]),
                        'gridOptions' => [
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'options' => ['id' => 'authusergrid'],
                        ]
            ]));
            ?>

</div>