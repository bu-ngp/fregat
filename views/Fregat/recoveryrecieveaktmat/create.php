<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fregat\Recoveryrecieveaktmat */

$this->title = 'Create Recoveryrecieveaktmat';
$this->params['breadcrumbs'][] = ['label' => 'Recoveryrecieveaktmats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recoveryrecieveaktmat-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
