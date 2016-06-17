<?php

use yii\helpers\Html;
use app\func\Proc;
use kartik\dynagrid\DynaGrid;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сотрудники';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="employee-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';
    $patienttype = filter_input(INPUT_GET, 'patienttype');

    echo DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'employeegrid'],
                'columns' => Proc::DGcols([
                    'columns' => [
                        'employee_id',
                        'idperson.auth_user_fullname',
                        'iddolzh.dolzh_name',
                        'idpodraz.podraz_name',
                        'idbuild.build_name',
                    ],
                    'buttons' => array_merge(
                            empty($foreign) ? [] : [
                                'choose' => function ($url, $model, $key) use ($foreign, $patienttype) {
                                    $customurl = Url::to([$foreign['url'], 'id' => $foreign['id'], 'patienttype' => $patienttype, $foreign['model'] => [$foreign['field'] => $model['employee_id']]]);
                                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-ok-sign"></i>', $customurl, ['title' => 'Выбрать', 'class' => 'btn btn-xs btn-success', 'data-pjax' => '0']);
                                }], Yii::$app->user->can('EmployeeEdit') ? [
                                        'update' => ['Fregat/employee/update', 'employee_id'],
                                        'delete' => ['Fregat/employee/delete', 'employee_id'],] : []
                            ),
                        ]),
                        'gridOptions' => [
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'panel' => [
                                'heading' => '<i class="glyphicon glyphicon-user"></i> ' . $this->title,
                                'before' => Yii::$app->user->can('EmployeeEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
                            ],
                        ]
            ]));
            ?>

</div>
