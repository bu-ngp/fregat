<?php

use yii\helpers\Html;
use app\func\Proc;
use kartik\dynagrid\DynaGrid;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\ImportemployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
//var_dump($this);
$this->title = 'Импорт сотрудников';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="importemployee-index">
    <?=
    DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'importemployeegrid'],
                'columns' => Proc::DGcols([
                    'columns' => [
                        'importemployee_combination',
                        'idpodraz.podraz_name',
                        'idbuild.build_name',
                    ],
                    'buttons' => [
                        'update' => ['Fregat/importemployee/update', 'importemployee_id'],
                        'deleteajax' => ['Fregat/importemployee/delete', 'importemployee_id'],
                    ],
                ]),
                'gridOptions' => [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'panel' => [
                        'heading' => '<i class="glyphicon glyphicon-user"></i> ' . $this->title,
                        'before' => Yii::$app->user->can('FregatImport') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
                    ],
                    'toolbar' => [
                        'base' => ['content' => \yii\bootstrap\Html::button('<i class="glyphicon glyphicon-floppy-disk"></i>', [
                                'id' => 'Importemployeeexcel',
                                'type' => 'button',
                                'title' => 'Экспорт в Excel',
                                'class' => 'btn btn-default button_export',
                                'onclick' => 'ExportExcel("' . $searchModel->formName() . '","' . \yii\helpers\Url::toRoute('Fregat/importemployee/toexcel') . '", $(this)[0].id );'
                            ]) . '{export}{dynagrid}',
                        ],
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