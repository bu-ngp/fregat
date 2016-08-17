<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\TrMatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Установленные комплектующие';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="tr-mat-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    echo DynaGrid::widget(Proc::DGopts([
                'options' => ['id' => 'trmatgrid'],
                'columns' => Proc::DGcols([
                    'columns' => [
                        'idMattraffic.idMaterial.material_name',
                        'idMattraffic.idMaterial.material_inv',
                        'idParent.material_name',
                        'idParent.material_inv',
                        'idMattraffic.mattraffic_number',
                        'idMattraffic.idMol.idperson.auth_user_fullname',
                        'idMattraffic.idMol.iddolzh.dolzh_name',
                    ],
                    'buttons' => array_merge(
                            /* empty($foreign) */ false ? [] : [
                                'chooseajax' => ['Fregat/tr-mat/assign-to-trrmmat']]
                    ),
                ]),
                'gridOptions' => [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'panel' => [
                        'heading' => '<i class="glyphicon glyphicon-align-paste"></i> ' . $this->title,
                    ],
                ]
    ]));
    ?>

</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Назад', Proc::GetPreviousURLBreadcrumbsFromSession(), ['class' => 'btn btn-info']) ?>
    </div>
</div>