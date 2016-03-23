<?php

namespace app\models\Config;

use Yii;
use yii\base\Model;

class AuthitemFilter extends Model {

    public $onlyrootauthitems;

    public function rules() {
        return [
            ['onlyrootauthitems', 'safe'],
        ];
    }
    
    public function attributeLabels() {
        return [
            'onlyrootauthitems' => 'Только основные авторизационные единицы',
        ];
    }

}
