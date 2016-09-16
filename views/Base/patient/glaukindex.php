<?php
\Yii::$app->getView()->registerJsFile(Yii::$app->request->baseUrl . '/js/patientglaukfilter.js');

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;
use app\models\Base\Patient;
use app\models\Glauk\Glaukuchet;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Patient\GlaukPatientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пациенты';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
    'addfirst' => [
        'label' => 'Регистр глаукомных пациентов',
        'url' => Url::toRoute('Base/patient/glaukindex'),
    ],
    'clearbefore' => true,
]);
?>
<div class="patient-glaukindex">
    <?php
    $patient_pol = Patient::VariablesValues('patient_pol');
    $glaukuchet_detect = Glaukuchet::VariablesValues('glaukuchet_detect');
    $glaukuchet_deregreason = Glaukuchet::VariablesValues('glaukuchet_deregreason');
    $glaukuchet_stage = Glaukuchet::VariablesValues('glaukuchet_stage');
    $glaukuchet_invalid = Glaukuchet::VariablesValues('glaukuchet_invalid');

    echo DynaGrid::widget(Proc::DGopts([
        'options' => ['id' => 'patientglaukgrid'],
        'columns' => Proc::DGcols([
            'buttonsfirst' => true,
            'columns' => [
                'patient_fam',
                'patient_im',
                'patient_ot',
                [
                    'attribute' => 'patient_dr',
                    'format' => 'date',
                ],
                [
                    'attribute' => 'patient_pol',
                    'filter' => $patient_pol,
                    'value' => function ($model) use ($patient_pol) {
                        return isset($patient_pol[$model->patient_pol]) ? $patient_pol[$model->patient_pol] : '';
                    },
                    'visible' => false,
                ],
                [
                    'attribute' => 'idFias.fias_city',
                    'visible' => false,
                ],
                [
                    'attribute' => 'idFias.fias_street',
                    'visible' => false,
                ],
                [
                    'attribute' => 'patient_dom',
                    'visible' => false,
                ],
                [
                    'attribute' => 'patient_korp',
                    'visible' => false,
                ],
                [
                    'attribute' => 'patient_kvartira',
                    'visible' => false,
                ],
                [
                    'attribute' => 'glaukuchets.glaukuchet_uchetbegin',
                    'format' => 'date',
                    'visible' => false,
                ],
                [
                    'attribute' => 'glaukuchets.glaukuchet_detect',
                    'filter' => $glaukuchet_detect,
                    'value' => function ($model) use ($glaukuchet_detect) {
                        return isset($glaukuchet_detect[$model->glaukuchets->glaukuchet_detect]) ? $glaukuchet_detect[$model->glaukuchets->glaukuchet_detect] : '';
                    },
                    'visible' => false,
                ],
                [
                    'attribute' => 'glaukuchets.glaukuchet_deregdate',
                    'format' => 'date',
                    'visible' => false,
                ],
                [
                    'attribute' => 'glaukuchets.glaukuchet_deregreason',
                    'filter' => $glaukuchet_deregreason,
                    'value' => function ($model) use ($glaukuchet_deregreason) {
                        return isset($glaukuchet_deregreason[$model->glaukuchets->glaukuchet_deregreason]) ? $glaukuchet_deregreason[$model->glaukuchets->glaukuchet_deregreason] : '';
                    },
                    'visible' => false,
                ],
                [
                    'attribute' => 'glaukuchets.glaukuchet_stage',
                    'filter' => $glaukuchet_stage,
                    'value' => function ($model) use ($glaukuchet_stage) {
                        return isset($glaukuchet_stage[$model->glaukuchets->glaukuchet_stage]) ? $glaukuchet_stage[$model->glaukuchets->glaukuchet_stage] : '';
                    },
                    'visible' => false,
                ],
                [
                    'attribute' => 'glaukuchets.glaukuchet_operdate',
                    'format' => 'date',
                    'visible' => false,
                ],
                [
                    'attribute' => 'glaukuchets.glaukuchet_invalid',
                    'filter' => $glaukuchet_invalid,
                    'value' => function ($model) use ($glaukuchet_invalid) {
                        return isset($glaukuchet_invalid[$model->glaukuchets->glaukuchet_invalid]) ? $glaukuchet_invalid[$model->glaukuchets->glaukuchet_invalid] : '';
                    },
                    'visible' => false,
                ],
                [
                    'attribute' => 'glaukuchets.glaukuchet_lastvisit',
                    'format' => 'date',
                ],
                [
                    'attribute' => 'glaukuchets.glaukuchet_lastmetabol',
                    'format' => 'date',
                    'visible' => false,
                ],
                [
                    'attribute' => 'glaukuchets.idEmployee.idperson.auth_user_fullname',
                    'label' => 'ФИО врача',
                ],
                [
                    'attribute' => 'glaukuchets.idEmployee.iddolzh.dolzh_name',
                    'label' => 'Специальность врача',
                    'visible' => false,
                ],
                [
                    'attribute' => 'glaukuchets.idEmployee.idpodraz.podraz_name',
                    'label' => 'Подразделение врача',
                    'visible' => false,
                ],
                [
                    'attribute' => 'glaukuchets.idEmployee.idbuild.build_name',
                    'label' => 'Местонахождение врача',
                ],
                [
                    'attribute' => 'glaukuchets.idClassMkb.code',
                    'visible' => false,
                ],
                [
                    'attribute' => 'glaukuchets.idClassMkb.name',
                    'visible' => false,
                ],
                [
                    'attribute' => 'glaukuchets.glpreps.glaukuchet_preparats',
                    'filter' => false,
                    'value' => function ($model) {
                        $a = '';
                        return isset($model->glaukuchets->glpreps[0]->glaukuchet_preparats) ? $model->glaukuchets->glpreps[0]->glaukuchet_preparats : '';
                    },
                    'headerOptions' => ['attr_fullname' => 'glaukuchets.glpreps.glaukuchet_preparats'],
                    'visible' => false,
                ],
                [
                    'attribute' => 'patient_username',
                    'visible' => false,
                ],
                [
                    'attribute' => 'patient_lastchange',
                    'format' => 'datetime',
                    'visible' => false,
                ],
                [
                    'attribute' => 'glaukuchets.glaukuchet_username',
                    'visible' => false,
                ],
                [
                    'attribute' => 'glaukuchets.glaukuchet_lastchange',
                    'format' => 'datetime',
                    'visible' => false,
                ],
            ],
            'buttons' => array_merge(
                Yii::$app->user->can('GlaukUserPermission') ? [
                    'update' => function ($url, $model, $key) {
                        $customurl = Url::to(['Base/patient/update', 'id' => $model->primarykey, 'patienttype' => 'glauk']);

                        if (!(isset($model->glaukuchets) || Yii::$app->user->can('GlaukOperatorPermission')))
                            return '';
                        else
                            return \yii\helpers\Html::a(isset($model->glaukuchets) ? '<i class="glyphicon glyphicon-pencil"></i>' : '<i class="glyphicon glyphicon-plus"></i>', $customurl, ['title' => isset($model->glaukuchets) ? 'Обновить' : 'Создать карту глаукомного пациента', 'class' => isset($model->glaukuchets) ? 'btn btn-xs btn-warning' : 'btn btn-xs btn-info', 'data-pjax' => '0']);
                    }] : [], Yii::$app->user->can('PatientRemoveRole') ? [
                'deleteajax' => ['Base/patient/delete', 'patient_id'],
            ] : []
            ),
        ]),
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'heading' => '<i class="glyphicon glyphicon-search"></i> ' . $this->title,
                'before' => Yii::$app->user->can('GlaukOperatorPermission') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить нового пациента', ['create', 'patienttype' => 'glauk'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
            ],
            'toolbar' => [
                'base' => ['content' => \yii\bootstrap\Html::a('<i class="glyphicon glyphicon-filter"></i>', ['glaukfilter'], [
                        'title' => 'Дополнительный фильтр',
                        'class' => 'btn btn-default filter_button'
                    ]) . \yii\bootstrap\Html::button('<i class="glyphicon glyphicon-floppy-disk"></i>', [
                        'id' => 'Patientglaukexcel',
                        'type' => 'button',
                        'title' => 'Экспорт в Excel',
                        'class' => 'btn btn-default button_export',
                        'onclick' => 'ExportExcel("PatientSearch","' . \yii\helpers\Url::toRoute('Base/patient/toexcel') . '", $(this)[0].id, {"PatientSearch[glaukuchets.glpreps.glaukuchet_preparats]": ""});'
                    ]) . '{export}{dynagrid}',
                ],
            ],
            'afterHeader' => $filter,
        ]
    ]));
    ?>

    <?php
    yii\bootstrap\Modal::begin([
        'header' => 'Дополнительный фильтр',
        'id' => 'PatientFilter',
        'options' => [
            'class' => 'modal_filter',
            'tabindex' => false, // чтобы работал select2 в модальном окне
        ],
    ]);
    yii\bootstrap\Modal::end();
    ?>

</div>
<div class="form-group">
    <div class="panel panel-default">
        <div class="panel-heading">
            <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Назад', Url::home(), ['class' => 'btn btn-info']) ?>
        </div>
    </div>
</div>