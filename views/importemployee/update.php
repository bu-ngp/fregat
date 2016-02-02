<?php

use yii\helpers\Html;
use app\models\Build;
use app\models\Podraz;

/* @var $this yii\web\View */
/* @var $model app\models\Importemployee */

$this->title = 'Update Importemployee: ' . ' ' . $model->importemployee_id;
$this->params['breadcrumbs'][] = ['label' => 'Importemployees', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->importemployee_id, 'url' => ['view', 'id' => $model->importemployee_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="importemployee-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=        
        $this->render('_form', [
        'model' => $model,
        'id_podraz' => Podraz::find()->select(['podraz_name'])->where(['podraz_id' => $model->id_podraz])->indexBy('podraz_id')->column(),
        'id_build' => Build::find()->select(['build_name'])->where(['build_id' => $model->id_build])->indexBy('build_id')->column(),
    ]) ?>

</div>
