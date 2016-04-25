<?php
\Yii::$app->getView()->registerJsFile('js/replacematerial.js');

use yii\helpers\Html;
use app\func\Proc;
use app\models\Fregat\Dolzh;
use app\models\Config\Authuser;
use app\models\Fregat\Podraz;
use app\models\Fregat\Build;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\TrOsnov */

$this->title = 'Добавить перемещаемую материальную ценность';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
            'model' => [$model, $Mattraffic, $Material, $Employee, new Dolzh, new Podraz, new Authuser, new Build], // $Material, $Employee для сохранения setsession при вводе инвентарника
        ]);
?>
<div class="tr-osnov-create">
    <div class="panel panel-<?= Yii::$app->params['panelStyle'] ?>">
        <div class="panel-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <?=
            $this->render('_form', [
                'model' => $model,
                'Mattraffic' => $Mattraffic,
                'Material' => $Material,
                'Employee' => $Employee,
                'mattraffic_number_max' => $mattraffic_number_max,
            ])
            ?>
        </div>
    </div>
</div>
