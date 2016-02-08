<?php

use yii\helpers\Html;
use app\func\Proc;
use kartik\dynagrid\DynaGrid;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сотрудники';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="employee-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    DynaGrid::widget(Proc::DGopts([
                'columns' => Proc::DGcols([
                    'columns' => [
                        'employee_id',
                        'employee_fio',
                        'iddolzh.dolzh_name',
                        'idpodraz.podraz_name',
                        'idbuild.build_name',
                    ],
                    'buttons' => array_merge(
                                isset($session[$foreignmodel]['foreign']) ? [
                                    'choose' => function ($url, $model, $key) use ($session, $foreignmodel) {
                                        $field = $session[$foreignmodel]['foreign']['field'];
                                        $customurl = Url::to([$session[$foreignmodel]['foreign']['url'], 'id' => $session[$foreignmodel]['foreign']['id'], $foreignmodel => [$field => $model['employee_id']]]);
                                        return \yii\helpers\Html::a('<i class="glyphicon glyphicon-ok-sign"></i>', $customurl, ['title' => 'Выбрать', 'class' => 'btn btn-xs btn-success']);
                                    }] : [], [
                                    'update' => ['employee/update', 'employee_id'],
                                    'delete' => ['employee/delete', 'employee_id'],])
                            ,
                        ]),
                        'gridOptions' => [
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'options' => ['id' => 'employeegrid'],
                        ]
            ]));
            ?>

</div>
