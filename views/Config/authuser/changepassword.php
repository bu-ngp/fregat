<?php

use yii\helpers\Html;
use app\func\Proc;

$this->title = 'Изменить пароль пользователя';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this,[
    'model' => $model,
]);
?>
<div class="authuser-changepassword">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
