<?php

namespace app\models\Config;

use Yii;
use yii\base\Model;

class AuthitemFilter extends Model {

    public $onlyrootauthitems_mark;

    public function rules() {
        return [            
            ['onlyrootauthitems_mark', 'safe'],
        ];
    }

    public function attributeLabels() {
        return [
            'onlyrootauthitems_mark' => 'Только основные авторизационные единицы',
        ];
    }

}
