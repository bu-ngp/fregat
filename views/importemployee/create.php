<?php

use yii\helpers\Html;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $model app\models\Importemployee */

$this->title = 'Создать словосочетание';
/*$this->params['breadcrumbs'][] = ['label' => 'Importemployees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/

$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);

//var_dump($this->params['breadcrumbs']);

?>
<div class="importemployee-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'id_podraz' => [],
        'id_build' => $id_build,
    ]) ?>

</div>
