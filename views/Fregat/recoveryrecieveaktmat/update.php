<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Recoveryrecieveaktmat */

$this->title = 'Update Recoveryrecieveaktmat: ' . $model->recoveryrecieveaktmat_id;
$this->params['breadcrumbs'][] = ['label' => 'Recoveryrecieveaktmats', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->recoveryrecieveaktmat_id, 'url' => ['view', 'id' => $model->recoveryrecieveaktmat_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="recoveryrecieveaktmat-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
