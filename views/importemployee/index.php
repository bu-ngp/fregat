<?php

use yii\helpers\Html;
use app\func\Proc;
use kartik\dynagrid\DynaGrid;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ImportemployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
//var_dump($this);
$this->title = 'Импорт сотрудников';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="importemployee-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    DynaGrid::widget(Proc::DGopts([
                'columns' => Proc::DGcols([
                    'columns' => [
                        'importemployee_combination',
                        'idpodraz.podraz_name',
                        'idbuild.build_name',
                    ],
                    'buttons' => [
                        'update' => ['importemployee/update', 'importemployee_id'],
                        'delete' => ['importemployee/delete', 'importemployee_id'],
                    ],
                ]),
                'gridOptions' => [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'options' => ['id' => 'importemployeegrid'],
                ]
    ]));
    ?>

</div>
