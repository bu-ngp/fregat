<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\helpers\Url;

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
    $patient_pol = [1 => 'Мужской', 2 => 'Женский'];
    $glaukuchet_detect = [1 => 'При обращении за лечением', 2 => 'При обращении по диспансеризации'];
    $glaukuchet_deregreason = [1 => 'Смерть', 2 => 'Миграция', 3 => 'Другое'];
    $glaukuchet_stage = [1 => 'I стадия', 2 => 'II стадия', 3 => 'III стадия', 4 => 'IV стадия'];
    $glaukuchet_rlocat = [1 => 'Федеральная', 2 => 'Региональная'];
    $glaukuchet_invalid = [1 => 'I группа', 2 => 'II группа', 3 => 'III группа'];

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
                        //  'visible' => false,    
                        ],
                        /* 'idFias.fias_city',
                          'idFias.fias_street', */
                        [
                            'attribute' => 'patient_dom',
                        //  'visible' => false,
                        ],
                        [
                            'attribute' => 'patient_korp',
                        //  'visible' => false,
                        ],
                        [
                            'attribute' => 'patient_kvartira',
                        // 'visible' => false,
                        ],
                        [
                            'attribute' => 'glaukuchets.glaukuchet_uchetbegin',
                            'format' => 'date',
                        // 'visible' => false,
                        ],
                        [
                            'attribute' => 'glaukuchets.glaukuchet_detect',
                            'filter' => $glaukuchet_detect,
                            'value' => function ($model) use ($glaukuchet_detect) {
                                return isset($glaukuchet_detect[$model->glaukuchet_detect]) ? $glaukuchet_detect[$model->glaukuchet_detect] : '';
                            },
                        //  'visible' => false,    
                        ],
                        [
                            'attribute' => 'glaukuchets.glaukuchet_deregdate',
                            'format' => 'date',
                        // 'visible' => false,
                        ],
                        [
                            'attribute' => 'glaukuchets.glaukuchet_deregreason',
                            'filter' => $glaukuchet_deregreason,
                            'value' => function ($model) use ($glaukuchet_deregreason) {
                                return isset($glaukuchet_deregreason[$model->glaukuchet_deregreason]) ? $glaukuchet_deregreason[$model->glaukuchet_deregreason] : '';
                            },
                        //  'visible' => false,    
                        ],
                        [
                            'attribute' => 'glaukuchets.glaukuchet_stage',
                            'filter' => $glaukuchet_stage,
                            'value' => function ($model) use ($glaukuchet_stage) {
                                return isset($glaukuchet_stage[$model->glaukuchet_stage]) ? $glaukuchet_stage[$model->glaukuchet_stage] : '';
                            },
                        //  'visible' => false,    
                        ],
                        [
                            'attribute' => 'glaukuchets.glaukuchet_operdate',
                            'format' => 'date',
                        // 'visible' => false,
                        ],
                        [
                            'attribute' => 'glaukuchets.glaukuchet_rlocat',
                            'filter' => $glaukuchet_rlocat,
                            'value' => function ($model) use ($glaukuchet_rlocat) {
                                return isset($glaukuchet_rlocat[$model->glaukuchet_rlocat]) ? $glaukuchet_rlocat[$model->glaukuchet_rlocat] : '';
                            },
                        //  'visible' => false,    
                        ],
                        [
                            'attribute' => 'glaukuchets.glaukuchet_invalid',
                            'filter' => $glaukuchet_invalid,
                            'value' => function ($model) use ($glaukuchet_invalid) {
                                return isset($glaukuchet_invalid[$model->glaukuchet_invalid]) ? $glaukuchet_invalid[$model->glaukuchet_invalid] : '';
                            },
                        //  'visible' => false,    
                        ],
                        [
                            'attribute' => 'glaukuchets.glaukuchet_lastvisit',
                            'format' => 'date',
                        // 'visible' => false,
                        ],
                        [
                            'attribute' => 'glaukuchets.glaukuchet_lastmetabol',
                            'format' => 'date',
                        // 'visible' => false,
                        ],
                        [
                            'attribute' => 'glaukuchets.idEmployee.idperson.auth_user_fullname',
                        // 'visible' => false,
                        ],
                        [
                            'attribute' => 'glaukuchets.idEmployee.iddolzh.dolzh_name',
                        // 'visible' => false,
                        ],
                        [
                            'attribute' => 'glaukuchets.idEmployee.idpodraz.podraz_name',
                        // 'visible' => false,
                        ],
                        [
                            'attribute' => 'glaukuchets.idEmployee.idbuild.build_name',
                        // 'visible' => false,
                        ],
                        [
                            'attribute' => 'glaukuchets.idClassMkb.code',
                        // 'visible' => false,
                        ],
                        [
                            'attribute' => 'glaukuchets.idClassMkb.name',
                        // 'visible' => false,
                        ],
                    ],
                        /* 'buttons' => array_merge(
                          Yii::$app->user->can('BuildEdit') ? [
                          'update' => ['Fregat/build/update', 'build_id'],
                          'delete' => ['Fregat/build/delete', 'build_id'],
                          ] : []
                          ), */
                ]),
                'gridOptions' => [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'panel' => [
                        'heading' => '<i class="glyphicon glyphicon-search"></i> ' . $this->title,
                        'before' => Yii::$app->user->can('GlaukOperatorPermission') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить нового пациента', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
                    ],
                ]
    ]));
    ?>

</div>