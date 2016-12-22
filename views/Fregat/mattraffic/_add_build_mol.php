<?php

use app\models\Config\Authuser;
use app\models\Fregat\Build;
use app\models\Fregat\Dolzh;
use app\models\Fregat\Podraz;
use yii\db\Query;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="addbuildmol-form">
    <?php $form = ActiveForm::begin(['options' => ['id' => $model->formName() . '-form', 'data-pjax' => true]]); ?>
    <div class="insideforms">

        <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?> panelblock">
            <div class="panel-heading"><?= Html::encode('Материально-ответственное лицо') ?></div>
            <div class="panel-body">
                <div class="errordialog" style="display: none;">

                </div>

                <?=
                $form->field($model, 'id_person')->widget(Select2::classname(), Proc::DGselect2(array(
                    'model' => $model,
                    'resultmodel' => new Authuser,
                    'fields' => array(
                        'keyfield' => 'id_person',
                        'resultfield' => 'auth_user_fullname',
                    ),
                    'placeholder' => '',
                    'resultrequest' => 'Fregat/authuser/selectinput',
                    'thisroute' => $this->context->module->requestedRoute,
                    'disabled' => true,
                )))->label('ФИО');
                ?>

                <?=
                $form->field($model, 'id_dolzh')->widget(Select2::classname(), Proc::DGselect2(array(
                    'model' => $model,
                    'resultmodel' => new Dolzh,
                    'fields' => array(
                        'keyfield' => 'id_dolzh',
                        'resultfield' => 'dolzh_name',
                    ),
                    'placeholder' => '',
                    'resultrequest' => 'Fregat/dolzh/selectinput',
                    'thisroute' => $this->context->module->requestedRoute,
                    'disabled' => true,
                )));
                ?>

                <?=
                $form->field($model, 'id_podraz')->widget(Select2::classname(), Proc::DGselect2(array(
                    'model' => $model,
                    'resultmodel' => new Podraz,
                    'fields' => array(
                        'keyfield' => 'id_podraz',
                        'resultfield' => 'podraz_name',
                    ),
                    'placeholder' => '',
                    'resultrequest' => 'Fregat/podraz/selectinput',
                    'thisroute' => $this->context->module->requestedRoute,
                    'disabled' => true,
                )));
                ?>

                <?=
                $form->field($model, 'id_build')->widget(Select2::classname(), Proc::DGselect2([
                    'model' => $model,
                    'resultmodel' => new Build,
                    'fields' => [
                        'keyfield' => 'id_build',
                        'resultfield' => 'build_name',
                    ],
                    'placeholder' => 'Выберете здание',
                    'resultrequest' => 'Fregat/build/selectinput',
                    'thisroute' => $this->context->module->requestedRoute,
                    'onlyAjax' => false,
                    'MethodQuery' => 'BuildAddMol',
                    'preloaddataajaxcondition' => function ($query) use ($employee_id) {
                        /** @var $query \yii\db\ActiveQuery */
                        return $query->andWhere(['not exists', (new Query())
                            ->select(['e2.id_build'])
                            ->from('employee e1')
                            ->innerJoin('employee e2', 'e1.id_dolzh = e2.id_dolzh and e1.id_podraz = e2.id_podraz and e1.id_person = e2.id_person and e1.employee_id = ' . $employee_id)
                            ->andWhere('build.build_id = e2.id_build')
                        ]);
                    },
                ]));
                ?>

            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::button('<i class="glyphicon glyphicon-ok"></i> Добавить', ['class' => 'btn btn-primary ', 'id' => 'ChangeBuildMolDialog_apply']) ?>
                <?= Html::button('<i class="glyphicon glyphicon-remove"></i> Отмена', ['class' => 'btn btn-danger', 'id' => 'ChangeBuildMolDialog_close']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
