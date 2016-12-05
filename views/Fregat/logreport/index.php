<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\Import\LogreportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

/*   'logreport_id',
  'logreport_date',
  'logreport_errors',
  'logreport_updates',
  'logreport_additions',
  'logreport_amount',
  'logreport_missed', */

$this->title = 'Сервис';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="logreport-index">
    <?php $form = ActiveForm::begin([
        'id' => 'configinpmort',
    ]); ?>
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Настройки') ?></div>
        <div class="panel-body">
            <?= $form->field($Importconfig, 'importconfig_do')->checkbox()->label(null, ['class' => 'control-label']); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <?=
    DynaGrid::widget(Proc::DGopts([
        'options' => ['id' => 'logreportgrid'],
        'columns' => Proc::DGcols([
            'columns' => [
                'logreport_id',
                [
                    'attribute' => 'logreport_executetime',
                    'format' => 'time',
                ],
                [
                    'attribute' => 'logreport_memoryused',
                    'value' => function ($model, $key, $index, $column) {
                        Yii::$app->formatter->sizeFormatBase = 1000;
                        return Yii::$app->formatter->asShortSize($model->logreport_memoryused);
                    }
                ],
                [
                    'attribute' => 'logreport_date',
                    'format' => 'date',
                ],
                'logreport_errors',
                'logreport_updates',
                'logreport_additions',
                'logreport_amount',
                'logreport_missed',
            ],
            'buttons' =>
                [
                    'download' => function ($url, $model) {
                        return Html::a('<i class="glyphicon glyphicon-download-alt"></i>', '@web/importreports/Отчет импорта в систему Фрегат N' . $model['logreport_id'] . '.xlsx', ['title' => 'Скачать отчет', 'class' => 'btn btn-xs btn-info', 'data-pjax' => '0']);
                    },
                    /*  'removeimport' => function ($url, $model) {
                          return Html::button('<i class="glyphicon glyphicon-remove"></i>', [
                              'type' => 'button',
                              'title' => 'Удалить импортированные записи',
                              'class' => 'btn btn-xs btn-danger',
                              'onclick' => 'ConfirmDeleteDialogToAjax("Вы уверены, что хотите удалить импортированные записи за ' . Yii::$app->formatter->asDate($model->logreport_date) . '?", "' . Yii::$app->getUrlManager()->createUrl(['Fregat/logreport/remove-import', 'id' => $model->primaryKey]) . '")'
                          ]);
                      },*/
                ],
        ]),
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'heading' => '<i class="glyphicon glyphicon-inbox"></i> ' . $this->title,
                'before' => Html::button('<i class="glyphicon glyphicon-trash"></i> Очистить отчеты', [
                    'type' => 'button',
                    'title' => 'Удалить',
                    'class' => 'btn btn-danger',
                    'onclick' => 'ConfirmDeleteDialogToAjax("Вы уверены, что хотите очистить все отчеты?", "' . Yii::$app->getUrlManager()->createUrl(['Fregat/logreport/clear']) . '")'
                ]),
            ],
        ]
    ]));
    ?>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::submitButton('<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => 'btn btn-primary', 'form' => 'configinpmort']) ?>
            </div>
        </div>
    </div>
</div>
        