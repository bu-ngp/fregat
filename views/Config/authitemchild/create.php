<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Config\Authitemchild */

$this->title = 'Create Authitemchild';
$this->params['breadcrumbs'][] = ['label' => 'Authitemchildren', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authitemchild-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
