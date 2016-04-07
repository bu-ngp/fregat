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

    public function SetFilter($FilterAttributes) {
        parse_str($FilterAttributes, $filterparams);
        if ($filterparams['AuthitemFilter']['onlyrootauthitems'] === '1') {            
            $filter .= ' ' . $this->attributeLabels()['onlyrootauthitems'] . ';';
        }

        return $filter;
    }

}
