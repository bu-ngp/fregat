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

    <?= $form->field($model, 'grupa_name')->textInput(['maxlength' => true, 'class' => 'form-control setsession inputuppercase', 'autofocus' => true]) ?>

    <?php ActiveForm::end(); ?>

    <?php
    if (!$model->isNewRecord) {
        $grupavid_main = app\models\Fregat\Grupavid::VariablesValues('grupavid_main');

        echo DynaGrid::widget(Proc::DGopts([
                    'options' => ['id' => 'grupavidgrid'],
                    'columns' => Proc::DGcols([
                        'columns' => [
                            'idmatvid.matvid_name',
                            [
                                'attribute' => 'grupavid_main',
                                'filter' => $grupavid_main,
                                'value' => function ($model) use ($grupavid_main) {
                                    return isset($grupavid_main[$model->grupavid_main]) ? $grupavid_main[$model->grupavid_main] : '';
                                },
                            ],
                        ],
                        'buttons' => [
                            'createmain' => function ($url, $model) {
                                $customurl = Yii::$app->getUrlManager()->createUrl(['Fregat/grupavid/createmain', 'grupavid_id' => $model->grupavid_id, 'id_grupa' => $model->id_grupa]);
                                return Html::button('<i class="glyphicon glyphicon-magnet"></i>', [
                                            'type' => 'button',
                                            'title' => 'Удалить',
                                            'class' => 'btn btn-xs btn-info',
                                            'onclick' => 'ConfirmDeleteDialogToAjax("Сделать вид материальной ценности основным?", "' . $customurl . '")'
                                ]);
                            },
                                    'deleteajax' => ['Fregat/grupavid/delete', 'grupavid_id'],
                                ],
                            ]),
                            'gridOptions' => [
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'panel' => [
                                    'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-paperclip"></i> Привязать к группе</h3>',
                                    'before' => Html::a('<i class="glyphicon glyphicon-download"></i> Добавить вид материальной ценности', ['Fregat/matvid/forgrupavid',
                                        'foreignmodel' => 'Grupavid',
                                        'url' => $this->context->module->requestedRoute,
                                        'field' => 'id_matvid',
                                        'id' => $model->primaryKey,
                                            ], ['class' => 'btn btn-success', 'data-pjax' => '0']),
                                ],
                            ]
                ]));
            }
            ?>



            <div class="form-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Назад', Proc::GetPreviousURLBreadcrumbsFromSession(), ['class' => 'btn btn-info']) ?>
                        <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-plus"></i> Создать' : '<i class="glyphicon glyphicon-edit"></i> Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'form' => 'Grupavidform']) ?>
            </div>
        </div> 
    </div>

</div>
