<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\func\Proc;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ImportemployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
//var_dump($this);
$this->title = 'Импорт сотрудников';
//$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this);

//var_dump($this->params['breadcrumbs']);
?>
<div class="importemployee-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'importemployee_id',
            'importemployee_combination',
            /* 'id_build',
              'id_podraz', */
            'idpodraz.podraz_name',
            'idbuild.build_name',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
