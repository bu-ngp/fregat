<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Config\Authuser */

$this->title = $model->auth_user_id;
$this->params['breadcrumbs'][] = ['label' => 'Authusers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authuser-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->auth_user_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->auth_user_id], [
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
            'auth_user_id',
            'auth_user_fullname',
            'auth_user_login',
            'auth_user_password',
        ],
    ]) ?>

</div>
