<?php
use app\models\Fregat\Material;
use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

\Yii::$app->getView()->registerJsFile('@web/js/osmotraktsend.js' . Proc::appendTimestampUrlParam(Yii::$app->basePath . '/web/js/osmotraktsend.js'));
\Yii::$app->getView()->registerJsFile('@web/js/osmotraktfilter.js' . Proc::appendTimestampUrlParam(Yii::$app->basePath . '/web/js/osmotraktfilter.js'));

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\OsmotraktSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Журнал осмотров материальных ценностей';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="osmotrakt-index">
    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';
    $material_writeoff = Material::VariablesValues('material_writeoff');

    echo DynaGrid::widget(Proc::DGopts([
        'options' => ['id' => 'osmotraktgrid'],
        'columns' => Proc::DGcols([
            'buttonsfirst' => true,
            'columns' => [
                'osmotrakt_id',
                [
                    'attribute' => 'idTrosnov.idMattraffic.idMaterial.idMatv.matvid_name',
                    'visible' => false,
                ],
                [
                    'attribute' => 'osmotrakt_date',
                    'format' => 'date',
                ],
                [
                    'attribute' => 'idTrosnov.idMattraffic.idMaterial.material_name',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return '<a data-pjax="0" href="' . Url::to(['Fregat/material/update', 'id' => $model->idTrosnov->idMattraffic->id_material]) . '">' . $model->idTrosnov->idMattraffic->idMaterial->material_name . '</a>';
                    }
                ],
                'idTrosnov.idMattraffic.idMaterial.material_inv',
                [
                    'attribute' => 'idTrosnov.idMattraffic.idMaterial.material_serial',
                    'visible' => false,
                ],
                [
                    'attribute' => 'idTrosnov.idMattraffic.idMaterial.material_writeoff',
                    'visible' => false,
                    'filter' => $material_writeoff,
                    'value' => function ($model) use ($material_writeoff) {
                        return isset($material_writeoff[$model->idTrosnov->idMattraffic->idMaterial->material_writeoff]) ? $material_writeoff[$model->idTrosnov->idMattraffic->idMaterial->material_writeoff] : '';
                    },
                ],
                [
                    'attribute' => 'idUser.idperson.auth_user_fullname',
                    'visible' => false,
                    'label' => 'ФИО пользоателя',
                ],
                [
                    'attribute' => 'idUser.iddolzh.dolzh_name',
                    'visible' => false,
                    'label' => 'Должность пользоателя',
                ],
                [
                    'attribute' => 'idTrosnov.idMattraffic.idMol.idperson.auth_user_fullname',
                    'visible' => false,
                    'label' => 'ФИО материально-ответственного лица',
                ],
                [
                    'attribute' => 'idTrosnov.idMattraffic.idMol.iddolzh.dolzh_name',
                    'visible' => false,
                    'label' => 'Должность материально-ответственного лица',
                ],
                'idTrosnov.idCabinet.cabinet_name',
                'idTrosnov.idMattraffic.idMol.idbuild.build_name',
                'idReason.reason_text',
                'osmotrakt_comment',
                [
                    'attribute' => 'idMaster.idperson.auth_user_fullname',
                    'label' => 'ФИО составителя акта',
                ],
                [
                    'attribute' => 'idMaster.iddolzh.dolzh_name',
                    'visible' => false,
                    'label' => 'Должность составителя акта',
                ],
                [
                    'attribute' => 'idTrosnov.idInstallakt.installakt_id',
                    'visible' => false,
                ],
            ],
            'buttons' => array_merge(
                empty($foreign) ? [
                    'downloadreport' => ['Fregat/osmotrakt/osmotrakt-report'],
                    'sendosmotrakt' => function ($url, $model) {
                        return \yii\bootstrap\Html::a('<i class="glyphicon glyphicon-send"></i>', ['send-osmotrakt-content', 'osmotrakt_id' => $model->primaryKey], [
                            'title' => 'Отправить акт в организацию по электронной почте',
                            'class' => 'btn btn-xs btn-success osmotraktsend'
                        ]);
                    },
                ] : [
                    'chooseajax' => ['Fregat/osmotrakt/assign-to-grid']], Yii::$app->user->can('OsmotraktEdit') ? [
                'update' => ['Fregat/osmotrakt/update', 'osmotrakt_id'],
                'deleteajax' => ['Fregat/osmotrakt/delete', 'osmotrakt_id'],
            ] : []
            ),
        ]),
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'heading' => '<i class="glyphicon glyphicon-search"></i> ' . $this->title,
                'before' => Yii::$app->user->can('OsmotraktEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
            ],
            'toolbar' => [
                'base' => ['content' => \yii\bootstrap\Html::a('<i class="glyphicon glyphicon-filter"></i>', ['osmotraktfilter'], [
                        'title' => 'Дополнительный фильтр',
                        'class' => 'btn btn-default filter_button',
                    ]) . \yii\bootstrap\Html::button('<i class="glyphicon glyphicon-floppy-disk"></i>', [
                        'id' => 'Osmotraktexcel',
                        'type' => 'button',
                        'title' => 'Экспорт в Excel',
                        'class' => 'btn btn-default button_export',
                        'onclick' => 'ExportExcel("OsmotraktSearch","' . \yii\helpers\Url::to('Fregat/osmotrakt/toexcel') . '", $(this)[0].id, undefined, ' . (YII_ENV === 'test' ? 0 : 1) . ');'
                    ]) . '{export}{dynagrid}',
                ],
            ],
            'afterHeader' => $filter,
        ]
    ]));
    ?>
</div>

<?php
yii\bootstrap\Modal::begin([
    'header' => 'Отправить акт осмотра в организацию по электронной почте',
    'id' => 'SendOsmotraktDialog',
    'options' => [
        'class' => 'modal_filter',
        'tabindex' => false, // чтобы работал select2 в модальном окне
    ],
]);

yii\bootstrap\Modal::end();

yii\bootstrap\Modal::begin([
    'header' => 'Дополнительный фильтр',
    'id' => 'OsmotraktFilter',
    'options' => [
        'class' => 'modal_filter',
        'tabindex' => false, // чтобы работал select2 в модальном окне
    ],
]);
yii\bootstrap\Modal::end();
?>
