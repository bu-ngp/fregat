<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
use app\func\Proc;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BuildSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Здания';
//$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="build-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <div class="btn-group">
        <?php
        echo Html::a('Добавить', ['create'], ['class' => 'btn btn-info']);
        if (!empty($selectelement)) {
            end($this->params['breadcrumbs']);
            echo Html::a('Выбрать', "#" /*[$this->params['breadcrumbs'][key($this->params['breadcrumbs']) - 1]['url']]*/, ['onclick'=>"ChooseItemGrid('".$this->params['breadcrumbs'][key($this->params['breadcrumbs']) - 1]['url']."','".$selectelement."','buildgrid');",  'class' => 'btn btn-success']);
        }
        ?>
    </div>
    <?=
    DynaGrid::widget([
        // 'export' => false,
        'options' => ['id' => 'dynagrid-1'],
        'columns' => [
            [
                'class' => 'kartik\grid\RadioColumn',
                'name' => 'buildgrid_check',
            //   'width' => '36px',
            //       'headerOptions' => ['class' => 'kartik-sheet-style'],
            ],
            ['class' => 'kartik\grid\SerialColumn'],
            //  'build_id',
            'build_name',
            ['class' => 'kartik\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        $customurl = Yii::$app->getUrlManager()->createUrl(['build/update', 'id' => $model['build_id']]);
                        return \yii\helpers\Html::a('<i class="glyphicon glyphicon-pencil"></i>', $customurl, ['title' => 'Обновить', 'class' => 'btn btn-xs btn-success']);
                    },
                            'delete' => function ($url, $model) {
                        $customurl = Yii::$app->getUrlManager()->createUrl(['build/delete', 'id' => $model['build_id']]);
                        return \yii\helpers\Html::a('<i class="glyphicon glyphicon-trash"></i>', $customurl, ['title' => 'Удалить', 'class' => 'btn btn-xs btn-danger']);
                    },
                        ],
                    ],
                ],
                'gridOptions' => [
                    'export' => false,
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'options' => ['id' => 'buildgrid'],
                    'panel' => [
                        'type' => GridView::TYPE_DEFAULT,
                    //  'heading'=>$heading,
                    ],
                ]
            ]);
            /*   var_dump($selectelement);
              end($this->params['breadcrumbs']);

              var_dump($this->params['breadcrumbs'][key($this->params['breadcrumbs']) - 1]['url']);
              var_dump(key(array_slice($this->params['breadcrumbs'], -1, 1, TRUE))); */
// var_dump(array_pop(array_keys($this->params['breadcrumbs'])));
             $this->registerJs("console.debug($('#grid1').length)", View::POS_END);
            ?>



</div>

<script type="text/javascript">
    //  $(document).ready(function() {
    // js script
    // var keys = $('#grid1').yiiGridView('getSelectedRows');
    //  console.debug($('#grid1').length)

// keys is an array consisting of the keys associated with the selected rows
    // });

</script>
