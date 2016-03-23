<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\BuildSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Авторизационные единицы';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="authitem-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    echo DynaGrid::widget(Proc::DGopts([
                'columns' => Proc::DGcols([
                    'columns' => array_merge(
                            ['description'], !isset($foreign['model']) || (isset($foreign['model']) && $foreign['model'] !== 'Authassignment') ? [
                                [
                                    'attribute' => 'type',
                                    'filter' => [1 => 'Роль', 2 => 'Операция'],
                                    'value' => function ($model) {
                                return $model->type == 1 ? 'Роль' : 'Операция';
                            },
                                ],
                                'name'
                                    ] : []
                    ),
                    'buttons' => array_merge(
                            empty($foreign) ? [] : [
                                'choose' => function ($url, $model, $key) use ($foreign) {
                                    $customurl = Url::to([$foreign['url'], 'id' => $foreign['id'], $foreign['model'] => [$foreign['field'] => $model['name']]]);
                                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-ok-sign"></i>', $customurl, ['title' => 'Выбрать', 'class' => 'btn btn-xs btn-success', 'data-pjax' => '0']);
                                }], Yii::$app->user->can('RoleEdit') ? [
                                        'update' => ['Config/authitem/update', 'name'],
                                        'delete' => ['Config/authitem/delete', 'name'],
                                            ] : []
                            ),
                        ]),
                        'gridOptions' => [
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'options' => ['id' => 'authitemgrid'],
                            'panel' => [
                                'heading' => '<i class="glyphicon glyphicon-align-justify"></i> ' . $this->title,
                                'before' => Yii::$app->user->can('RoleEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
                            ],
                            'toolbar' => [
                                'base' => ['content' => \yii\bootstrap\Html::a('<i class="glyphicon glyphicon-filter"></i>', ['filter'], [
                                        //  'type' => 'button',
                                        'title' => 'Дополнительный фильтр',
                                        'class' => 'btn btn-default filter_button'
                                    ]) . \yii\bootstrap\Html::button('<i class="glyphicon glyphicon-floppy-disk"></i>', [
                                        'id' => 'Authitemexcel',
                                        'type' => 'button',
                                        'title' => 'Экспорт в Excel',
                                        'class' => 'btn btn-default button_export',
                                        'onclick' => 'ExportExcel("AuthitemSearch","' . \yii\helpers\Url::toRoute('Config/authitem/toexcel') . '", $(this)[0].id );'
                                    ]) . '{export}{dynagrid}',
                                ],
                            ],
                        // 'afterHeader' => '<div class="panel panel-warning"><div class="panel-heading authitemgrid-filter"></div></div>',
                        ]
            ]));
            ?>


            <?php
            yii\bootstrap\Modal::begin([
                'header' => 'Дополнительный фильтр',
                'id' => 'Authitemfilter',
                'options' => ['class' => 'modal_filter',],
            ]);
            yii\bootstrap\Modal::end();

            $this->registerJs(
                    "$(document).on('ready pjax:success', function() {
        $('.filter_button').click(function(e){
           e.preventDefault(); //for prevent default behavior of <a> tag.
           var tagname = $(this)[0].tagName;      
           $('#Authitemfilter').modal('show')
                      .find('.modal-body')
                      .load($(this).attr('href')); 
       });
    });
");
            ?>

            <?php
           /* $this->registerJs(
                    'jQuery(document).ready(function($){
                $(document).ready(function () {
                    $("body").on("beforeSubmit", "form#authitemfilter-form", function () {
                        var form = $(this);
                        // return false if form still have some validation errors
                        if (form.find(".has-error").length) 
                        {
                            return false;
                        }
                        // submit form
                        $.ajax({
                            url    : form.attr("action"),
                            type   : "post",
                            data   : form.serialize(),
                            success: function (response) 
                            {

                                $("#Authitemfilter").modal("toggle");                               
           // $.pjax.reload({container:"#dynagrid-1-pjax"});  //Reload GridView
          $("body").on("beforeFilter", "#authitemgrid" , function(event) {
            console.debug(event)
            return true;
        }); 
           $("#authitemgrid").yiiGridView("applyFilter");

                            },
                            error  : function () 
                            {
                                console.log("internal server error");
                            }
                        });
                        return false;
                     });
                    });

    });'
            );*/
            ?>

</div>