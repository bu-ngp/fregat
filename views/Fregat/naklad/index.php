<?php
\Yii::$app->getView()->registerJsFile(Yii::$app->request->baseUrl . '/js/nakladfilter.js');

use app\func\Proc;
use kartik\dynagrid\DynaGrid;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Fregat\NakladSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Журнал требований-накладных';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);
?>
    <div class="naklad-index">
        <?=
        DynaGrid::widget(Proc::DGopts([
            'options' => ['id' => 'nakladgrid'],
            'columns' => Proc::DGcols([
                'columns' => [
                    'naklad_id',
                    [
                        'attribute' => 'naklad_date',
                        'format' => 'date',
                    ],
                    [
                        'attribute' => 'idMolGot.idperson.auth_user_fullname',
                        'label' => 'ФИО МОЛ получателя',
                    ],
                    [
                        'attribute' => 'idMolGot.iddolzh.dolzh_name',
                        'label' => 'Должность МОЛ получателя',
                    ],
                    [
                        'attribute' => 'idMolGot.idpodraz.podraz_name',
                        'label' => 'Подразделение МОЛ получателя',
                    ],
                    [
                        'attribute' => 'idMolRelease.idperson.auth_user_fullname',
                        'label' => 'ФИО МОЛ отправителя',
                    ],
                    [
                        'attribute' => 'idMolRelease.iddolzh.dolzh_name',
                        'label' => 'Должность МОЛ отправителя',
                    ],
                    [
                        'attribute' => 'idMolRelease.idpodraz.podraz_name',
                        'label' => 'Подразделение МОЛ отправителя',
                    ],
                ],
                'buttons' => array_merge(Yii::$app->user->can('NakladEdit') ? [
                    'update' => ['Fregat/naklad/update'],
                    'deleteajax' => ['Fregat/naklad/delete'],
                ] : []
                ),
            ]),
            'gridOptions' => [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    'heading' => '<i class="glyphicon glyphicon-bell"></i> ' . $this->title,
                    'before' => Yii::$app->user->can('NakladEdit') ? Html::a('<i class="glyphicon glyphicon-plus"></i> Добавить', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0']) : '',
                ],
                'toolbar' => [
                    'base' => ['content' => \yii\bootstrap\Html::a('<i class="glyphicon glyphicon-filter"></i>', ['nakladfilter'], [
                            'title' => 'Дополнительный фильтр',
                            'class' => 'btn btn-default filter_button'
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
    'header' => 'Дополнительный фильтр',
    'id' => 'NakladFilter',
    'options' => [
        'class' => 'modal_filter',
        'tabindex' => false, // чтобы работал select2 в модальном окне
    ],
]);
yii\bootstrap\Modal::end();
?>