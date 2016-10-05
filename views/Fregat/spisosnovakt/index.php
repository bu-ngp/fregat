<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\SpisosnovaktSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Журнал списания основных средств';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="spisosnovakt-index">
    <?=
    DynaGrid::widget(Proc::DGopts([
        'options' => ['id' => 'spisosnovaktgrid'],
        'columns' => Proc::DGcols([
            'columns' => [
                'spisosnovakt_id',
                [
                    'attribute' => 'spisosnovakt_date',
                    'format' => 'date',
                ],
                [
                    'attribute' => 'idMol.idperson.auth_user_fullname',
                    'label' => 'ФИО МОЛ',
                ],
                [
                    'attribute' => 'idMol.iddolzh.dolzh_name',
                    'label' => 'Должность МОЛ',
                ],
                [
                    'attribute' => 'idMol.idpodraz.podraz_name',
                    'label' => 'Подразделение МОЛ',
                ],
                [
                    'attribute' => 'idEmployee.idperson.auth_user_fullname',
                    'label' => 'ФИО иного лица',
                ],
                [
                    'attribute' => 'idEmployee.iddolzh.dolzh_name',
                    'label' => 'Должность иного лица',
                ],
                'idSchetuchet.schetuchet_kod',
                'idSchetuchet.schetuchet_name',
            ],
            'buttons' => array_merge(Yii::$app->user->can('SpisosnovaktEdit') ? [
                'update' => ['Fregat/spisosnovakt/update'],
                'deleteajax' => ['Fregat/spisosnovakt/delete'],
            ] : []
            ),
        ]),
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'heading' => '<i class="glyphicon glyphicon-paste"></i> ' . $this->title,
                'before' => Yii::$app->user->can('SpisosnovaktEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
            ],
        ]
    ]));
    ?>
</div>
