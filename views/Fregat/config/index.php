<?php
use yii\helpers\Html;
use app\func\Proc;
use yii\helpers\Url;

$this->title = 'Настройки';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
            'addfirst' => [
                'label' => 'Фрегат',
                'url' => Url::toRoute('Fregat/fregat/index'),
            ],
            'clearbefore' => true,
        ]);

echo Html::a('Импорт данных', ['import'], ['class' => 'btn btn-primary']);

?>

