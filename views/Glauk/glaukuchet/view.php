<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Glauk\Glaukuchet */

$this->title = $model->glaukuchet_id;
$this->params['breadcrumbs'][] = ['label' => 'Glaukuchets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="glaukuchet-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->glaukuchet_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->glaukuchet_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'glaukuchet_id',
            'glaukuchet_uchetbegin',
            'glaukuchet_detect',
            'glaukuchet_deregdate',
            'glaukuchet_deregreason',
            'glaukuchet_stage',
            'glaukuchet_operdate',
            'glaukuchet_rlocat',
            'glaukuchet_invalid',
            'glaukuchet_lastvisit',
            'glaukuchet_lastmetabol',
            'id_patient',
            'id_employee',
            'id_class_mkb',
            'glaukuchet_comment',
        ],
    ]) ?>

</div>
