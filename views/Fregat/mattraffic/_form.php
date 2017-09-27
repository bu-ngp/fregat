<?php

use app\models\Config\Authuser;
use app\models\Fregat\Build;
use app\models\Fregat\Dolzh;
use app\models\Fregat\Employee;
use app\models\Fregat\Material;
use app\models\Fregat\Podraz;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Fregat\Matvid;
use app\models\Fregat\Izmer;
use kartik\select2\Select2;
use kartik\dynagrid\DynaGrid;
use app\func\Proc;
use yii\web\Session;
use kartik\datecontrol\DateControl;
use app\models\Fregat\Mattraffic;
use app\models\Fregat\Recoveryrecieveakt;
use app\models\Fregat\Recoveryrecieveaktmat;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Material */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mattraffic-form">

    <?php
    $form = ActiveForm::begin();
    ?>
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Материальная ценность') ?></div>
        <div class="panel-body">

            <?= $form->field($Material, 'material_tip', ['enableClientValidation' => false])->dropDownList(array_merge([0 => ''], Material::VariablesValues('material_tip')), ['class' => 'form-control', 'disabled' => true]) ?>

            <?=
            $form->field($Material, 'id_matvid', ['enableClientValidation' => false])->widget(Select2::classname(), Proc::DGselect2([
                'model' => $Material,
                'resultmodel' => new Matvid,
                'fields' => [
                    'keyfield' => 'id_matvid',
                    'resultfield' => 'matvid_name',
                ],
                'placeholder' => 'Выберете вид материальной ценности',
                'fromgridroute' => 'Fregat/matvid/index',
                'resultrequest' => 'Fregat/matvid/selectinput',
                'thisroute' => $this->context->module->requestedRoute,
                'disabled' => true,
                'setsession' => false,
                'onlyAjax' => false,
            ]));
            ?>

            <?= $form->field($Material, 'material_name', ['enableClientValidation' => false])->textInput(['class' => 'form-control', 'disabled' => true]) ?>

            <?= $form->field($Material, 'material_inv', ['enableClientValidation' => false])->textInput(['class' => 'form-control', 'disabled' => true]) ?>

            <?= $form->field($Material, 'material_number', ['enableClientValidation' => false])->textInput(['class' => 'form-control', 'disabled' => true]) ?>


            <?=
            $form->field($Material, 'id_izmer')->widget(Select2::classname(), Proc::DGselect2([
                'model' => $Material,
                'resultmodel' => new Izmer,
                'fields' => [
                    'keyfield' => 'id_izmer',
                    'resultfield' => 'izmer_name',
                ],
                'placeholder' => 'Выберете единицу измерения',
                'fromgridroute' => 'Fregat/izmer/index',
                'resultrequest' => 'Fregat/izmer/selectinput',
                'thisroute' => $this->context->module->requestedRoute,
                'disabled' => true,
                'setsession' => false,
                'onlyAjax' => false,
            ]));
            ?>

            <?= $form->field($Material, 'material_price', ['enableClientValidation' => false])->textInput(['class' => 'form-control', 'disabled' => true]) ?>

            <?= $form->field($Material, 'material_serial', ['enableClientValidation' => false])->textInput(['class' => 'form-control', 'disabled' => true]) ?>

            <?=
            $form->field($Material, 'material_release', ['enableClientValidation' => false])->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
                'options' => [
                    'options' => ['placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                ],
                'disabled' => true,
            ])
            ?>

        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
    $mattraffic_tip = Mattraffic::VariablesValues('mattraffic_tip');
    echo DynaGrid::widget(Proc::DGopts([
        'options' => ['id' => 'mattraffic_mols_grid'],
        'columns' => Proc::DGcols([
            'columns' => [
                [
                    'attribute' => 'mattraffic_date',
                    'format' => 'date',
                ],
                [
                    'attribute' => 'mattraffic_tip',
                    'filter' => $mattraffic_tip,
                    'value' => function ($model) use ($mattraffic_tip) {
                        return isset($mattraffic_tip[$model->mattraffic_tip]) ? $mattraffic_tip[$model->mattraffic_tip] : '';
                    },
                ],
                'mattraffic_number',
                [
                    'attribute' => 'idMol.idperson.auth_user_fullname',
                    'label' => 'Материально-ответственное лицо',
                ],
                [
                    'attribute' => 'idMol.iddolzh.dolzh_name',
                    'label' => 'Должность материально-ответственного лица',
                ],
                [
                    'attribute' => 'idMol.idbuild.build_name',
                    'label' => 'Здание материально-ответственного лица',
                ],
                [
                    'attribute' => 'mattraffic_username',
                    'visible' => false,
                ],
                [
                    'attribute' => 'mattraffic_lastchange',
                    'format' => 'datetime',
                    'visible' => false,
                ],
            ],
        ]),
        'gridOptions' => [
            'dataProvider' => $dataProvider_mattrafficmols,
            'filterModel' => $searchModel_mattrafficmols,
            'panel' => [
                'heading' => '<i class="glyphicon glyphicon-random"></i> Материально-ответственные лица',
            ],
        ]
    ]));
    ?>

    <?php
    $form = ActiveForm::begin([
        'id' => 'MattrafficMolform',
    ]);
    ?>
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode('Сменить материально-ответственное лицо на') ?></div>
        <div class="panel-body">

            <?=
            $form->field($model, 'mattraffic_date')->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
                'options' => [
                    'options' => ['placeholder' => 'Выберите дату ...', 'class' => 'form-control'],
                ],
                'disabled' => true,
            ])
            ?>

            <?=
            $form->field($model, 'id_mol')->widget(Select2::classname(), Proc::DGselect2([
                'model' => $model,
                'resultmodel' => new Employee,
                'fields' => [
                    'keyfield' => 'id_mol',
                ],
                'placeholder' => 'Выберете материально-ответственное лицо',
                'fromgridroute' => 'Fregat/employee/index',
                'resultrequest' => 'Fregat/employee/selectinputemloyee',
                'thisroute' => $this->context->module->requestedRoute,
                'methodquery' => 'selectinput',
            ]));
            ?>

        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::submitButton('<i class="glyphicon glyphicon-plus"></i> Сменить', ['class' => 'btn btn-success', 'form' => 'MattrafficMolform']) ?>
                <?php // Html::button('<i class="glyphicon glyphicon-calendar"></i> Добавить здание выбранному МОЛ', ['id' => 'ChangeBuildMOL', 'class' => 'btn btn-info', 'onclick' => 'DialogBuildAddOpen()']) ?>

                <?=
                Html::a('<i class="glyphicon glyphicon-calendar"></i> Добавить здание выбранному МОЛ', ['change-build-mol-content'], [
                    'id' => 'ChangeBuildMOL',
                    'title' => 'Добавить здание выбранному МОЛ',
                    'class' => 'btn btn-info'
                ]);
                ?>
            </div>
        </div>
    </div>

</div>

<?php
yii\bootstrap\Modal::begin([
    'header' => 'Добавить здание материально-ответственному лицу',
    'id' => 'ChangeBuildMolDialog',
    'options' => [
        'class' => 'modal_filter',
        'tabindex' => false, // чтобы работал select2 в модальном окне
    ],
]);
yii\bootstrap\Modal::end();
?>
