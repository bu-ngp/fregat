<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Grupa */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grupa-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'Grupavidform',
    ]);
    ?>

    <?= $form->field($model, 'grupa_name')->textInput(['maxlength' => true, 'class' => 'form-control setsession']) ?>

    <?php ActiveForm::end(); ?>

    <?php
    if (!$model->isNewRecord) {
        echo DynaGrid::widget(Proc::DGopts([
                    'columns' => Proc::DGcols([
                        'columns' => [
                            [
                                'class' => '\kartik\grid\RadioColumn',
                                'showClear' => false,
                                'radioOptions' => function($model, $key, $index, $column) {
                                    return $model['grupavid_main'] == 1 ? ['checked' => 'checked'] : [];
                                }
                                    ],
                                    'idmatvid.matvid_name',
                                    [
                                        'attribute' => 'grupavid_main',
                                        'filter' => [0 => 'Нет', 1 => 'Да'],
                                        'value' => function ($model) {
                                    return $model->grupavid_main == 0 ? 'Нет' : 'Да';
                                },
                                    ],
                                ],
                                'buttons' => [
                                    'delete' => ['Fregat/grupavid/delete', 'grupavid_id']
                                ],
                            ]),
                            'gridOptions' => [
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'options' => ['id' => 'grupavidgrid'],
                                'panel' => [
                                    'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-paperclip"></i> Привязать к группе</h3>',
                                    'before' => Html::a('<i class="glyphicon glyphicon-download"></i> Добавить вид материальной ценности', ['Fregat/matvid/forgrupavid',
                                        'foreignmodel' => 'Grupavid', //substr($model->className(), strrpos($model->className(), '\\') + 1),
                                        'url' => $this->context->module->requestedRoute,
                                        'field' => 'id_matvid',
                                        'id' => $model->primaryKey,
                                            // 'id' => $_GET['id'],
                                            ], ['class' => 'btn btn-success', 'data-pjax' => '0']),
                                ],
                            ]
                ]));
            }
            ?>



            <div class="form-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Grupavidform']) ?>
            </div>
        </div> 
    </div>

</div>
