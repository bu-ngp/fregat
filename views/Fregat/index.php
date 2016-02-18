<?php

use app\func\Proc;
use yii\helpers\Url;

$this->title = 'Материальные ценности';
$this->params['breadcrumbs'] = Proc::Breadcrumbs($this, [
            'addfirst' => [
                'label' => 'Фрегат',
                'url' => Url::toRoute('Fregat/fregat/index'),
            ],
            'clearbefore' => true,
        ]);
