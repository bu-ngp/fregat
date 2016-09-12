<?php

use yii\helpers\Html;
use app\func\Proc;
use kartik\dynagrid\DynaGrid;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Специальности сотрудников';
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
            'buttonsfirst' => true,
            'columns' => [
                'employee_id',
                'idperson.auth_user_fullname',
                'iddolzh.dolzh_name',
                'idpodraz.podraz_name',
                'idbuild.build_name',
            ],
            'buttons' => array_merge(
                empty($foreign) ? [] : [
                    'chooseajax' => ['Fregat/employee/assign-to-material']
                ], (Yii::$app->user->can('EmployeeEdit') || Yii::$app->user->can('EmployeeBuildEdit') || Yii::$app->user->can('EmployeeSpecEdit') ? [
                'update' => ['Fregat/employee/update', 'employee_id']] : []), (Yii::$app->user->can('EmployeeEdit') ? [
                'deleteajax' => ['Fregat/employee/delete', 'employee_id']] : [])
            ),
        ]),
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'heading' => '<i class="glyphicon glyphicon-user"></i> ' . $this->title,
                'before' => Yii::$app->user->can('EmployeeEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['//Config/authuser/index', 'emp' => true], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
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