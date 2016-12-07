<?php
use app\func\Proc;
use app\models\Fregat\Recoveryrecieveaktmat;
use kartik\dynagrid\DynaGrid;
use yii\bootstrap\Html;
use yii\helpers\Url;

$recoveryrecieveaktmat_repaired = Recoveryrecieveaktmat::VariablesValues('recoveryrecieveaktmat_repaired');
echo DynaGrid::widget(Proc::DGopts([
    'options' => ['id' => 'recoverysendmat_grid'],
    'columns' => Proc::DGcols([
        'columns' => [
            'id_recoverysendakt',
            [
                'attribute' => 'idRecoverysendakt.recoverysendakt_date',
                'format' => 'date',
            ],
            [
                'attribute' => 'recoveryrecieveaktmat_date',
                'format' => 'date',
            ],
            'recoveryrecieveaktmat_result',
            [
                'attribute' => 'recoveryrecieveaktmat_repaired',
                'filter' => $recoveryrecieveaktmat_repaired,
                'value' => function ($model) use ($recoveryrecieveaktmat_repaired) {
                    return isset($recoveryrecieveaktmat_repaired[$model->recoveryrecieveaktmat_repaired]) ? $recoveryrecieveaktmat_repaired[$model->recoveryrecieveaktmat_repaired] : '';
                },
            ],
            'idTrMatOsmotr.id_osmotraktmat',
        ],
        'buttons' => array_merge([
            'recoveryrecieveaktmatreport' => function ($url, $model) use ($params) {
                return Html::button('<i class="glyphicon glyphicon-list"></i>', [
                    'type' => 'button',
                    'title' => 'Скачать акт получения материалов от сторонней организации',
                    'class' => 'btn btn-xs btn-default',
                    'onclick' => 'DownloadReport("' . Url::to(['Fregat/recoveryrecieveaktmat/recoveryrecieveaktmat-report']) . '", null, {id: ' . $model->id_recoverysendakt . '} )'
                ]);
            },
        ],
            Yii::$app->user->can('RecoveryEdit') ? [
                'recoverysendaktmatview' => function ($url, $model) use ($params) {
                    $customurl = Yii::$app->getUrlManager()->createUrl(['Fregat/recoverysendakt/update', 'id' => $model->id_recoverysendakt]);
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $customurl, ['title' => 'Открыть', 'class' => 'btn btn-xs btn-success', 'data-pjax' => '0']);
                },
            ] : []
        ),
    ]),
    'gridOptions' => [
        'dataProvider' => $dataProvider_recoverysendmat,
        'filterModel' => $searchModel_recoverysendmat,
        'panel' => [
            'heading' => '<i class="glyphicon glyphicon-wrench"></i> Восстановление, как материал',
        ],
    ]
]));