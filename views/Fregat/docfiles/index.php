<?php
\Yii::$app->getView()->registerJsFile('js/docfiles.js');

use app\func\Proc;
use kartik\file\FileInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\DocfilesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Загруженные файлы';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
<div class="docfiles-index">

    <?php $form = ActiveForm::begin(); ?>

    <?=
    $form->field($model, 'docFile')->widget(FileInput::classname(), [
        'pluginOptions' => [
            'uploadUrl' => Url::to(['Fregat/docfiles/create']),
            'dropZoneEnabled' => false,
            'previewZoomSettings' => [
                'image' => [
                    'width' => 'auto',
                    'height' => '100%',
                ],
            ],
        ],
        'pluginEvents' => [
            "fileuploaded" => 'function(event, data, previewId, index) { UploadedFiles("docfilesgrid", event, data); }'
        ]
    ]);
    ?>

    <?php ActiveForm::end(); ?>

    <?php
    $result = Proc::GetLastBreadcrumbsFromSession();
    $foreign = isset($result['dopparams']['foreign']) ? $result['dopparams']['foreign'] : '';

    echo DynaGrid::widget(Proc::DGopts([
        'options' => ['id' => 'docfilesgrid'],
        'columns' => Proc::DGcols([
            'columns' => [
                'docfiles_ext',
                'docfiles_name',
                [
                    'attribute' => 'docfiles_hash',
                    'visible' => false,
                ],
            ],
            'buttons' => array_merge(
                empty($foreign) ? [] : [
                    'chooseajax' => ['Fregat/docfiles/assign-to-select2']
                ], Yii::$app->user->can('DocfilesEdit') ? [
                'deleteajax' => ['Fregat/docfiles/delete'],
            ] : []
            ),
        ]),
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'heading' => '<i class="glyphicon glyphicon-file"></i> ' . $this->title,
            ],
        ]
    ])); ?>
</div>
